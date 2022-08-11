<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivityController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSession;

use Validator;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    public function login(){
        $credentials = request(['username', 'password']);
        $device = request('device');

        $credentials['username'] = addslashes(trim(strtolower($credentials['username'])));
        $credentials['password'] = addslashes(trim(strtolower($credentials['password'])));

        $validator = Validator::make($credentials, [
            'username' => 'required|string|between:5,14',
            'password' => 'required|string|min:6',
        ],[
            'username.required' => "El Usuario no puede estar vacio",
            'username.between' => "Usuario no valido",
            'username.string' => "Usuario no valido",
            'password.required' => "La Contraseña no puede estar vacia",
            'password.string' => "Contraseña no valida",
            'password.min' => "Contraseña no valida",
        ]
        );

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(json_encode(['error' => ['El usuario y/o contraseña son incorrectos.']]), 400);
        }

        $user = auth()->user();
        
        if(!$user->state){
            return response()->json(json_encode(['error' => ['El usuario se encuentra bloqueado, por favor contacte un administrador para solicitar su desbloqueo.']]), 400);
        }
        
        $sesion = $this->respondWithToken($token);
        
        unset($user->email_verified_at);

        //$log = new ActivityController();
        //$activity = $log->store('Acceso al sistema', $device);

        //$this->storeActiveSesion($user, $token);

        $response = [
            'status' => 'success',
            "sesion" => $sesion,
            "user" => $user
        ];

        return $response;
    }

    public function signup(Request $request) {
        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|between:2,100',
            'first_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'username' => 'required|string|between:7,14|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ],[
            'last_name.required' => "El Apellido no puede estar vacío.",
            'first_name.required' => "El Nombre no puede estar vacío.",
            'username.required' => "El Nombre de Usuario no puede estar vacío.",
            'username.unique' => "El Nombre de Usuario ingresado se encuentra en uso.",
            'email.required' => "El Email no puede estar vacío.",
            'email.unique' => "El Email ingresado se encuentra en uso.",
            'password.required' => "La Contraseña no puede estar vacia.",
            'password_confirmation.required' => "La Contraseña debe confirmarse.",
            'password_confirmation.confirmed' => "La Contraseña debe confirmarse.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)]
        ));

        if($user){
            return response()->json([
                'status' => 'success',
                'message' => 'Usuario registrado correctamente.',
            ], 200);
        }

    }

    public function me(){
        return response()->json(auth()->user());
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Sesion Finalizada correctamente.']);
    }

    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token){
        return [
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ];
    }
}