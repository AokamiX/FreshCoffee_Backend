<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegistroRequest;

class AuthController extends Controller
{
    public function register(RegistroRequest $request){
        // Validar el Registro
        $data = $request->validated();

        //Crear Usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        // Retornar una Respuesta
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }
    
    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return [
            'user' => null
        ];
    }
    
    public function login(LoginRequest $request){
        $data = $request->validated();

        //Revisar el password

        if(!Auth::attempt($data)){
            return response([
                'errors' => ['El email o el password son incorrectos']
            ], 422);
        }

        //Autenticar al usuario

        $user = Auth::user();
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }
}
