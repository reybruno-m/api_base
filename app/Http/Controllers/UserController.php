<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Mail\UserEmailController;

use App\Models\User;

use Validator;

class UserController extends Controller
{
    public $activity;
    public $notify;

    public function __construct(){
        $this->middleware('auth:api');
        $this->activity = new ActivityController();
        $this->notify = new UserEmailController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|between:2,100',
            'first_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'username' => 'required|string|between:7,14|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'profile_id' => 'nullable|exists:profiles,id',
        ],[
            'last_name.required' => "El Apellido no puede estar vacío.",
            'last_name.string' => "El Apellido posee un formato invalido.",
            'last_name.between' => "El Apellido debe contener entre :min y :max caracteres.",
            'first_name.required' => "El Nombre no puede estar vacío.",
            'first_name.string' => "El Nombre posee un formato invalido.",
            'first_name.between' => "El Nombre debe contener entre :min y :max caracteres.",
            'email.required' => "El Email no puede estar vacío.",
            'email.email' => "El Email posee un formato invalido.",
            'email.max' => "El Email no puede contener mas de :max caracteres.",
            'email.unique' => "El Email ya se encuentra registrado.",
            'username.required' => "El Nombre de Usuario no puede estar vacío.",
            'username.string' => "El Nombre de Usuario posee un formato invalido.",
            'username.between' => "El Nombre de Usuario debe contener entre :min y :max caracteres.",
            'username.unique' => "El Nombre de usuario ya se encuentra registrado.",
            'password.required' => "La Contraseña no puede estar vacia.",
            'password_confirmation.required' => "La Contraseña debe confirmarse.",
            'password_confirmation.confirmed' => "La Contraseña debe confirmarse.",
            'profile_id.exists' => "El perfil que intenta asignar no existe.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $record = User::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)],
            ['uuid' => (string) Str::uuid()]
        ));

        if($record){

            $this->activity->store("users", "REGISTER", $record->id, "Usuario Registrado");

            $resNotify = $this->notify->createAccount($record);

            return response()->json([
                'status' => 'success',
                'message' => 'Usuario registrado correctamente.',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $record = User::find($id);

        if($record){
            return $record;
        }

        return response()->json(json_encode(['error' => ['El registro no existe.']]), 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $record = User::find($id);

        if(!$record){
            return response()->json(json_encode(['error' => ['El registro no existe.']]), 400);
        }

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|between:2,100',
            'first_name' => 'required|string|between:2,100',
            'email' => 'required|email|max:100',
            'username' => 'required|string|between:7,14',
            'password' => 'required|string|confirmed|min:8',
            'profile_id' => 'nullable|exists:profiles,id',
        ],[
            'last_name.required' => "El Apellido no puede estar vacío.",
            'last_name.string' => "El Apellido posee un formato invalido.",
            'last_name.between' => "El Apellido debe contener entre :min y :max caracteres.",
            'first_name.required' => "El Nombre no puede estar vacío.",
            'first_name.string' => "El Nombre posee un formato invalido.",
            'first_name.between' => "El Nombre debe contener entre :min y :max caracteres.",
            'email.required' => "El Email no puede estar vacío.",
            'email.email' => "El Email posee un formato invalido.",
            'email.max' => "El Email no puede contener mas de :max caracteres.",
            'username.required' => "El Nombre de Usuario no puede estar vacío.",
            'username.string' => "El Nombre de Usuario posee un formato invalido.",
            'username.between' => "El Nombre de Usuario debe contener entre :min y :max caracteres.",
            'password.required' => "La Contraseña no puede estar vacia.",
            'password_confirmation.required' => "La Contraseña debe confirmarse.",
            'password_confirmation.confirmed' => "La Contraseña debe confirmarse.",
            'profile_id.exists' => "El perfil que intenta asignar no existe.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        # Verifica si el nombre de usuario se encuentra en uso por otra persona.
        $usernameInUse = User::where('username', $request->username)->where('id', '!=', $id)->count();

        if($usernameInUse){
            return response()->json(json_encode(['error' => ['El nombre de usuario ingresado ya se encuentra registrado.']]), 400);
        }

        # Verifica si el nombre de usuario se encuentra en uso por otra persona.
        $emailInUse = User::where('email', $request->email)->where('id', '!=', $id)->count();

        if($emailInUse){
            return response()->json(json_encode(['error' => ['El email ingresado ya se encuentra registrado.']]), 400);
        }

        $aryMsg = [];

        # Copia los datos antes de modificarlos para comparar.
        $aryOld = $record;

        $record->username = $request->username;
        $record->password = $request->password;
        $record->last_name = $request->last_name;
        $record->first_name = $request->first_name;

        # Si el email cambió debo solicitar que se valide.
        if($aryOld->email != $request->email){
            $record->email = $request->email;
            $record->email_verified_at = null;
            $record->uuid = (string) Str::uuid();
            array_push($aryMsg, "Detectamos que se modificó la dirección de email, enviamos un link de validación a $request->email");
        }

        $record->phone_number = $request->phone_number;
        $record->profile_id = $request->profile_id;
        $record->state = ($request->state) ? $request->state : $record->state;

        $record->save();

        if($record){

            $this->activity->store("users", "UPDATE", $record->id, "Usuario Actualizado");

            if($record->uuid != ""){
                $resNotify = $this->notify->emailUpdate($record);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Usuario actualizado correctamente.',
                'changes' => $aryMsg
            ], 200);
        }

        return response()->json(json_encode(['error' => ['El registro no pudo ser actualizado.']]), 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //
    }
}
