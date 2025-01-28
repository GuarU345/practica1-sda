<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ValidationController extends Controller
{
    public function viewVerifyCode(): View
    {
        return view('verify-code');
    }
}
