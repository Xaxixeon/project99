<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class CustomerVerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request)
    {
        $customer = $request->user('customer');

        if ($customer->hasVerifiedEmail()) {
            return redirect()->route('customer.dashboard');
        }

        $request->fulfill();

        return redirect()
            ->route('customer.dashboard')
            ->with('status', 'verification-complete');
    }
}
