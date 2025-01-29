<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCode;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\View\View;

class ValidationController extends Controller
{
    public function viewVerifyCode(): View
    {
        return view('verify-code');
    }

    public function generateCode(User $user)
    {
        try {
            $userCode = UserCode::create([
                'user_id' => $user->id,
                'code' => random_int(000000, 999999),
                'expires_at' => Carbon::now()->addMinutes(5)
            ]);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Error al tratar de generar el codigo']);
        } catch (ErrorException $e) {
            return back()->withErrors(['error' => 'No se pudo realizar la petición']);
        }
    }
}
