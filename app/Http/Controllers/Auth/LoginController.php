<?php

namespace App\Http\Controllers\Auth;

use App\Constants\AuthConst;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = AuthConst::REDIRECT_ROUTE;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validate the user login request.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'g-recaptcha-response' => 'required',
            'password' => 'required|string',
        ]);

        if ($this->isRecaptchaBot($request)) {
            throw ValidationException::withMessages([
                $this->username() => 'Anda terdeteksi sebagai bot/spam, coba lagi nanti.',
            ]);
        }
    }

    protected function isRecaptchaBot(Request $request): bool
    {
        // Here, you might want to access the recaptcha score

        $score = RecaptchaV3::verify($request->get('g-recaptcha-response'), 'login');

        return $score < 0.3;
    }
}
