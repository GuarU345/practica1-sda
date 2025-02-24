<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCodeRequest;
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use App\Models\UserCode;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

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
            // Desactivar códigos previos
            UserCode::where('user_id', $user->id)->update(['active' => false]);

            // Generar código aleatorio
            $randomCode = rand(100000, 999999);

            // Guardar en la base de datos (encriptado)
            UserCode::create([
                'user_id' => $user->id,
                'code' => Hash::make($randomCode), // Importante recordar que esto está hasheado
                'active' => true,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]);

            // Generar enlace de verificación
            $signedUrl = URL::temporarySignedRoute(
                'verify-code',
                now()->addMinutes(30),
                ['userId' => $user->id]
            );

            // Enviar correo
            Mail::to($user->email)->send(new TwoFactorCodeMail($randomCode, $signedUrl));

            return true; // Indica que todo salió bien
        } catch (QueryException $e) {
            Log::error('Error en la base de datos al generar código', ['exception' => $e->getMessage()]);
            return false;
        } catch (Exception $e) {
            Log::error('Error general al generar código', ['exception' => $e->getMessage()]);
            return false;
        }
    }

    public function verifyCode(UserCodeRequest $request, $userId)
    {
        $currentUser = User::find($userId);
        $validateData = $request->validated();

        // Buscar el código del usuario actual
        $currentCode = UserCode::where('user_id', $currentUser->id)->where('active', true)->first();

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
        if (Hash::check($validateData['code'], $currentCode->code)) {
            $currentCode->update(['active' => false]);
            Auth::login($currentUser);
            return redirect()->route('dashboard')->with('message', 'Código validado exitosamente');
        } else {
            return back()->withErrors(['error' => 'Código incorrecto.']);
        }
    }
}
