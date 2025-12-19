<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerEmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request)
    {
        $customer = $request->user('customer');

        if ($customer && $customer->hasVerifiedEmail()) {
            return redirect()->route('customer.dashboard');
        }

        return view('auth.verify-email');
    }
}
