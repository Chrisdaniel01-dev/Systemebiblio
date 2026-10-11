<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Log;

class AuthController extends Controller
{
    // ✅ AJOUTEZ CETTE MÉTHODE MANQUANTE
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        // This is for API logout - web logout is handled by webLogout method
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function webLogin(Request $request)
    {
        // Add CSRF token validation debugging
        logger('CSRF Token: ' . $request->_token);
        logger('Session ID: ' . $request->session()->getId());

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        logger('Login attempt for: ' . $request->email);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            logger('Login successful for: ' . $request->email);

            $request->session()->regenerate();

            // Log the login activity (optional - don't fail if table missing)
            try {
                if (Schema::hasTable('logs')) {
                    Log::create([
                        'users_id' => Auth::id(),
                        'action' => 'Connexion au système',
                        'created_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                logger('Failed to log login activity: ' . $e->getMessage());
            }

            logger('Redirecting to dashboard');
            return redirect()->intended('/dashboard');
        }

        logger('Login failed for: ' . $request->email . ' - Invalid credentials');
        return back()->withErrors([
            'email' => 'Identifiants invalides.',
        ])->withInput($request->except('password'));
    }

    public function webLogout(Request $request)
    {
        // Log the logout activity
        try {
            Log::create([
                'users_id' => Auth::id(),
                'action' => 'Déconnexion du système',
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if logging fails
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}