<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\Mail\UserEmailController;
use App\Models\User;
use App\Models\Profile;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public $activity;
    public $notify;

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'signup', 'validateEmail', 'forgottenPwd', 'updatePwd']]);
        $this->activity = new ActivityController();
        $this->notify = new UserEmailController();
    }

    # Iniciar sesión.
    public function login(){
        $credentials = request(['username', 'password']);

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

        $this->activity->store("users", "LOGIN", $user->id, "Acceso al Sistema");

        $allowed = Profile::with('functions', 'functions.functions')->find($user->profile_id);

        $response = [
            'status' => 'success',
            "session" => $sesion,
            "user" => $user,
            "allowed" => $allowed
        ];

        return $response;
    }

    # Registrarse.
    public function signup(Request $request) {
        $userClass = new UserController();
        return $userClass->store($request);
    }

    # Obtener usuario.
    public function me(){
        response()->json(auth()->user());
    }

    # Finalizar sesión.
    public function logout(){

        auth()->logout();

        $this->activity->store("users", "LOGOUT", null, "Usuario Deslogueado.");

        return response()->json(['message' => 'Sesion Finalizada correctamente.']);
    }

    # Renovar sesión.
    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }

    # Calcular tiempo sesión.
    protected function respondWithToken($token){
        return [
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ];
    }

    # [Email] Valida una cuenta con un enlace de verificación.
    public function validateEmail($uuid){

        $record = User::where('uuid', $uuid)->first();

        if(!$record){
            return response()->json(json_encode(['error' => ['El código de validación no existe.']]), 400);
        }

        $record->email_verified_at = date("Y-m-d H:i:s");
        $record->uuid = null;
        $record->state = 1;
        $record->save();

        $this->activity->store("users", "EMAIL", $record->id, "Email Confirmado");

        return response()->json([
            'success' => true, 
            'message' => "Cuenta verificada correctamente."
        ], 200 );

    }

    # [Email] Solicitar restablecimiento de clave.
    public function forgottenPwd(Request $request){
        $record = User::where('email', $request->email)->first();

        $msg = "Si el email se encuentra registrado, reenviaremos un link de recuperación de clave.";

        if($record){
            $record->uuid = (string) Str::uuid();
            $record->save();


            if($record->email_verified_at == '' || is_null($record->email_verified_at)){
                $resNotify = $this->notify->createAccount($record);
                $msg = "Para solicitar un link de recuperación de clave, primero debe verificar su cuenta. Reenviaremos el link a su email.";
            }else{
                $resNotify = $this->notify->forgottenPassword($record);
            }
        }

        return response()->json([
            'success' => true, 
            'message' => $msg
        ], 200 );
    }

    # Actualiza la clave de usuario a partir de un enlace de restablecimiento.
    public function updatePwd(Request $request, $uuid){
        
        $record = User::where('uuid', $uuid)->first();

        if(!$record){
            return response()->json(json_encode(['error' => ['El código de verificación no es valido.']]), 400);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:8',
        ],[
            'password.required' => "La Contraseña no puede estar vacia.",
            'password_confirmation.required' => "La Contraseña debe confirmarse.",
            'password_confirmation.confirmed' => "La Contraseña debe confirmarse.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $record->uuid = null;
        $record->password = Hash::make($request->password);
        $record->save();

        $this->activity->store("users", "UPDATE", $record->id, "Contraseña modificada correctamente.");

        return response()->json([
            'success' => true, 
            'message' => "Clave actualizada correctamente."
        ], 200 );
    }
}