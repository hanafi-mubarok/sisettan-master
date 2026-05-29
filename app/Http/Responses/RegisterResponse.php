<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        // Keep newly registered user logged out and send them to login page.
        Auth::guard('web')->logout();
        $request->session()->regenerate();

        return redirect()
            ->route('login')
            ->with('registration_success', 'Pendaftaran berhasil. Tunggu Verifikasi Internal untuk mengakses halaman.');
    }
}
