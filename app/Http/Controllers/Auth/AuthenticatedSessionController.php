<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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

    public function store(LoginRequest $request)
    {
        $validator = Validator::make($request->all(),[
            'email'    => 'required|email',
            'password' => 'required'

        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => true,
                'message' => 'User login successfully.',
                'data'    => $user
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Password don't Match."
            ]);
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
