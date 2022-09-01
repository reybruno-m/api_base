<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FunctionProfile;
//use App\Models\Profile;
//use App\Models\Functions;

use Validator;

class FunctionProfileController extends Controller
{
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
        return FunctionProfile::all();
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
            'function_id' => 'required|exists:functions,id',
            'profile_id' => 'required|exists:profiles,id',
        ],[
            'function_id.required' => "La función no puede estar vacía.",
            'function_id.exists' => "La función que intenta registrar no existe.",
            'profile_id.required' => "El perfil no puede estar vacio.",
            'profile_id.exists' => "EL perfil que intenta registrar no existe.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $exist = FunctionProfile::where('function_id', $request->function_id)->where('profile_id', $request->profile_id)->count();

        if($exist){
            return response()->json(json_encode(['error' => ['La función ya existe para el perfil.']]), 400);
        }

        $record = new FunctionProfile();
        $record->function_id = $request->function_id;
        $record->profile_id = $request->profile_id;
        $record->save();

        if($record){
            $this->activity->store("functions_profiles", "STORE", "Función $record->id Creada");

            return response()->json([
                'status' => 'success',
                'message' => 'Registro creado correctamente.',
            ], 200);
        }

        return response()->json(json_encode(['error' => ['El registro no pudo ser creado.']]), 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = FunctionProfile::find($id);

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

        $record = FunctionProfile::find($id);

        if(!$record){
            return response()->json(json_encode(['error' => ['El registro que intenta actualizar no existe.']]), 400);
        }

        $validator = Validator::make($request->all(), [
            'function_id' => 'required|exists:functions,id',
            'profile_id' => 'required|exists:profiles,id',
        ],[
            'function_id.required' => "La función no puede estar vacía.",
            'function_id.exists' => "La función que intenta registrar no existe.",
            'profile_id.required' => "El perfil no puede estar vacio.",
            'profile_id.exists' => "EL perfil que intenta registrar no existe.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $exist = FunctionProfile::where('function_id', $request->function_id)
                    ->where('profile_id', $request->profile_id)
                    ->count();

        if($exist){
            return response()->json(json_encode(['error' => ['No existen datos para actualizar.']]), 400);
        }

        $record->function_id = $request->function_id;
        $record->profile_id = $request->profile_id;
        $record->save();

        if($record){
            $this->activity->store("functions_profiles", "UPDATE", "Función $record->id Actualizada");

            return response()->json([
                'status' => 'success',
                'message' => 'Registro actualizado correctamente.',
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
    public function destroy($id)
    {
        $record = FunctionProfile::find($id);

        if($record){
            $delete = FunctionProfile::where('id', '=', $id)->delete();
    
            if($delete){
                $this->activity->store("functions_profiles", "DELETE", "Función $record->id Eliminada");

                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro eliminado correctamente.',
                ], 200);
            }
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'El Registro que intenta eliminar, no existe.',
            ], 200);
        }
        
        return response()->json(json_encode(['error' => ['El registro no pudo ser eliminado.']]), 400);
    }
}
