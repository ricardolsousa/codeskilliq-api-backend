<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Registro de usuário com papel
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'in:user,admin', // Certifique-se de que o papel seja 'user' ou 'admin'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role, // Defina o papel do usuário
        ]);

        $token = $user->createToken('MyApp')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    // Login de usuário
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $token = auth()->user()->createToken('MyApp')->accessToken;
            return response()->json(['token' => $token, 'name' => auth()->user()->name, 'email' => auth()->user()->email], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // Informações do usuário autenticado
    public function user(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    // Logout de usuário
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }
}

