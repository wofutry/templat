<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'status' => 'active'])) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('home'));
    }

    public function forgotPassword()
    {
        return view('pages.auth.forgot-password');
    }

    public function sendEmailForgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
    }

    public function recoverPassword(Request $request)
    {
        return view('pages.auth.recover-password', ['token' => $request->token, 'email' => $request->email]);
    }

    //change password from recovery form
    public function changePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
        ? redirect(route('login'))->with('success', __($status))
        : back()->withErrors(['email' => [__($status)]]);
    }

    public function register()
    {
        return view('pages.auth.register');
    }

    public function registerAccount(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:4|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'id_user_level' => 'required|numeric|exists:user_levels,id',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);

        event(new Registered($user));

        return redirect(route('home'))->with('success', 'Account Create Successfully. Please check you email for verification');
    }

    public function verify()
    {
        return view('pages.auth.verify-email');
    }

    public function verifyAccount(Request $request, $id, $hash)
    {
        // $request->fulfill();
        $user = User::find($id);
        // dd($user);

        if ($id != $user->getKey()) {
            throw new AuthorizationException();
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // return redirect('/home');
        return redirect()->route('login')->with('verified', true);
    }

    public function resendVerifyEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}
