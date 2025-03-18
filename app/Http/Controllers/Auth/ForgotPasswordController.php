<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendPasswordResetLink;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $credentials = $this->credentials($request);

        dispatch(new SendPasswordResetLink($credentials));

        return $this->sendResetLinkResponse($request, trans('passwords.sent'));
    }

    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns|exists:users,email',
            'g-recaptcha-response' => 'required',
        ], [
            'email.exists' => trans('passwords.user'),
        ]);

        if ($this->isRecaptchaBot($request)) {
            throw ValidationException::withMessages([
                'email' => 'Anda terdeteksi sebagai bot/spam, coba lagi nanti.',
            ]);
        }
    }

    protected function isRecaptchaBot(Request $request): bool
    {
        $score = RecaptchaV3::verify($request->get('g-recaptcha-response'), 'forgot');

        if ($score === null || $score < 0.3) {
            Log::warning('Low reCAPTCHA score detected.', [
                'ip' => $request->ip(),
                'email' => $request->input('email'),
            ]);

            return true;
        }

        return false;
    }
}
