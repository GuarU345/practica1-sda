<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use ReCaptcha\ReCaptcha;

class AuthController extends Controller
{
    /**
     * Muestra la vista del formulario de inicio de sesión.
     */
    public function viewLogin(): View
    {
        $serverPort = request()->server('SERVER_PORT') == 8001 ? 'servidor 1' : 'servidor 2';
        return view('auth.login', compact('serverPort'));
    }

    /**
     * Muestra la vista del formulario de registro de usuario.
     */
    public function viewRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Muestra la vista del panel de control del usuario.
     */
    public function viewDashboard(): View
    {
	$serverPort = request()->server('SERVER_PORT') == 8001 ? 'servidor 1' : 'servidor 2';
        return view('dashboard',compact('serverPort'));
    }

    /**
     * Registra un nuevo usuario en la aplicación.
     *
     * @param UserRegisterRequest $request Datos validados del formulario de registro.
     * @return RedirectResponse Redirige al login con un mensaje de éxito o devuelve un error.
     */
    public function register(UserRegisterRequest $request)
    {
        $validatedData = $request->validated();

        $recaptcha = new ReCaptcha(config('services.recaptcha.secret_key'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
        if (!$response->isSuccess()) {
            return back()->withErrors(['errors' => 'Por favor, verifica que no eres un robot.']);
        }

        try {
            // Se crea un nuevo usuario con los datos validados.
            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']) // Se encripta la contraseña.
            ]);

            return redirect()->route('login')->with('message', 'Usuario Registrado Correctamente');
        } catch (QueryException $e) {
            // Manejo de error si hay un problema con la base de datos.
            Log::error('Se produjo un error', ['exception' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al tratar de registrarte']);
        } catch (ErrorException $e) {
            // Manejo de error si ocurre una excepción genérica.
            Log::error('Se produjo un error', ['exception' => $e->getMessage()]);
            return back()->withErrors(['error' => 'No se pudo realizar la petición']);
        }
    }

    /**
     * Maneja el inicio de sesión de un usuario.
     *
     * @param UserLoginRequest $request Datos validados del formulario de inicio de sesión.
     * @return RedirectResponse Redirige a la verificación de código si es exitoso o devuelve un error.
     */
    public function login(UserLoginRequest $request)
    {
        $validatedData = $request->validated();
        $userExists = User::where('email', $validatedData['email'])->first();

        $recaptcha = new ReCaptcha(config('services.recaptcha.secret_key'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
        if (!$response->isSuccess()) {
            return back()->withErrors(['errors' => 'Por favor, verifica que no eres un robot.']);
        }

        try {
            // Se verifica que el usuario exista y la contraseña sea correcta.
            if ($userExists && Hash::check($validatedData['password'], $userExists->password)) {
                // Se genera un código de verificación para el usuario.
                $success = ValidationController::generateCode($userExists);
                if ($success) {
                    return back()->with('message', 'Se te ha enviado un correo a tu email');
                } else {
                    return back()->withErrors(['error' => 'No se pudo enviar el código de verificación. Intenta nuevamente.']);
                }
            } else {
                return back()->withErrors(['error' => 'Credenciales Invalidas']);
            }
        } catch (QueryException $e) {
            // Manejo de error si hay un problema con la base de datos.
            Log::error('Se produjo un error', ['exception' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al tratar de iniciar sesión']);
        } catch (ErrorException $e) {
            // Manejo de error si ocurre una excepción genérica.
            Log::error('Se produjo un error', ['exception' => $e->getMessage()]);
            return back()->withErrors(['error' => 'No se pudo realizar la petición']);
        }
    }

    /**
     * Cierra la sesión del usuario actual.
     *
     * @return RedirectResponse Redirige al login con un mensaje de sesión cerrada.
     */
    public function logout()
    {
        Auth::logout();

        // Se invalida la sesión y se regenera el token para mayor seguridad.
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Sesión Cerrada Correctamente');
    }
}
