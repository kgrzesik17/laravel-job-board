<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only(['email', 'password']);
        $remember = $request->filled('remember');  // remember me checkbox

        if(Auth::attempt($credentials, $remember)) {
            return redirect()->intended('/');  // UX feature. will jump back to where the user wanted to go before he needed to log in
        } else {
            return redirect()->back()
                ->with('error', 'Invalid credentials');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
