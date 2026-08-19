<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('admin')->user();
            if (isset($user->isactive) && ! $user->isactive) {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Esta conta de administrador está suspensa ou desativada.'])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('admins.index'));
        }

        if (Auth::guard('barbearia')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('barbearia')->user();
            if (isset($user->isactive) && ! $user->isactive) {
                Auth::guard('barbearia')->logout();
                return back()->withErrors(['email' => 'Esta conta de barbearia está suspensa ou desativada.'])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('barbearias.dashboard'));
        }

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();
            if (isset($user->isactive) && ! $user->isactive) {
                Auth::guard('web')->logout();
                return back()->withErrors(['email' => 'Sua conta de usuário está suspensa ou desativada.'])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('users.index'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();
        Auth::guard('barbearia')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
