<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function viewLogin(): View
    {
        return view('auth.login');
    }

    public function viewRegister(): View
    {
        return view('auth.register');
    }

    public function viewDashboard(): View
    {
        return view('dashboard');
    }

    public function register(UserRegisterRequest $request)
    {
        $validatedData = $request->validated();

        try {
            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password'])
            ]);

            return redirect()->route('login')->with(['message' => 'Usuario Registrado Correctamente']);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Error al tratar de registrarte']);
        } catch (ErrorException $e) {
            return back()->withErrors(['error' => 'No se pudo realizar la petición']);
        }
    }

    public function login(UserLoginRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $userExists = User::where('email', $validatedData->email)->first();

            if (!$userExists || !Hash::check($validatedData->password, $userExists->password)) {
                return back()->withErrors(['error' => 'Credenciales Invalidas']);
            }

            return redirect()->route('verify-code')->with(['message' => 'Usuario Logueado Correctamente']);
        } catch (QueryException $e) {
            Log::error('Se produjo un error', ['exception' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al tratar de iniciar sesión']);
        } catch (ErrorException $e) {
            Log::error('Se produjo un error', ['exception' => $e->getMessage()]);
            return back()->withErrors(['error' => 'No se pudo realizar la petición']);
        }
    }
}
