<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\ActivityController;

use App\Models\User;

use Validator;

class UserController extends Controller
{
    public $activity;

    public function __construct(){
        $this->middleware('auth:api');
        $this->activity = new ActivityController();
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|between:2,100',
            'first_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'username' => 'required|string|between:7,14|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'profile_id' => 'nullable|exists:profiles,id',
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
            'profile_id.exists' => "El perfil que intenta asignar no existe.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)]
        ));

        if($user){

            $this->activity->store("users", "REGISTER", "Usuario Registrado");

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
    public function show($id)
    {
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
    public function update(Request $request, $id)
    {
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
