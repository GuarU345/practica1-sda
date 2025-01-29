<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCodeRequest;
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use App\Models\UserCode;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ValidationController extends Controller
{
    /**
     * Muestra la vista del formulario para validar el codigo.
     */
    public function viewVerifyCode($userId)
    {
        $user = User::find($userId);
        return view('verify-code', ['userId' => $user->id]);
    }

    public static function generateCode($user)
    {
        try {
            //desactivar los demas codigos que tiene el usuario
            UserCode::where('user_id', $user->id)->update(['active' => false]);

            $userCode = UserCode::create([
                'user_id' => $user->id,
                'code' => random_int(000000, 999999),
                'active' => TRUE,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]);

            //generar email y agregarle el codigo recientemente generado
            Mail::to($user->email)->send(new TwoFactorCodeMail($userCode->code));
        } catch (QueryException $e) {
            Log::error('Se produjo un error', ['exception' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al tratar de generar el codigo']);
        } catch (ErrorException $e) {
            Log::error('Se produjo un error', ['exception' => $e->getMessage()]);
            return back()->withErrors(['error' => 'No se pudo realizar la petición']);
        }
    }

    public function verifyCode(UserCodeRequest $request, $userId)
    {
        $currentUser = User::find($userId);
        $validateData = $request->validated();

        // Buscar el código del usuario actual
        $currentCode = UserCode::where('user_id', $currentUser->id)->where('active', TRUE)->first();

        // Verificar si existe un código activo
        if (!$currentCode) {
            return back()->withError(['error' => 'No hay un código activo.']);
        }

        // Verificar si el código ha expirado (más de 5 minutos)
        $expirationTime = Carbon::parse($currentCode->expires_at)->addMinutes(5);

        if (now()->greaterThan($expirationTime)) {
            return back()->withErrors(['error' => 'El código ha expirado.']);
        }

        // Comparar el código ingresado con el registrado
        if ($validateData['code'] === $currentCode->code) {
            $currentCode->update(['active' => false]);
            Auth::login($currentUser);
            return redirect()->route('dashboard')->with('message', 'Código validado exitosamente');
        } else {
            return back()->withErrors(['error' => 'Código incorrecto.']);
        }
    }
}
