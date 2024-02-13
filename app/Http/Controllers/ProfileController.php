<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

use Validator;

class ProfileController extends Controller
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
        return Profile::all();
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
            'name' => 'required|string|between:2,75',
            'description' => 'nullable|string',
        ],[
            'name.required' => "El Nombre del perfil no puede estar vacía.",
            'name.string' => "El Nombre del perfil posee un formato no admitido.",
            'name.between' => "El Nombre del perfil debe contener entre :min - :max caracteres.",
            'description.string' => "La descripción del perfil posee un formato no admitido.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $record = new Profile();
        $record->name = $request->name;
        $record->description = $request->description;
        $record->save();

        if($record){
            $this->activity->store("profiles", "STORE", $record->id, "Perfil $record->id Creado");

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
        $record = Profile::find($id);

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
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,75',
            'description' => 'nullable|string',
        ],[
            'name.required' => "El Nombre del perfil no puede estar vacío.",
            'name.string' => "El Nombre del perfil posee un formato no admitido.",
            'name.between' => "El Nombre del perfil debe contener entre :min - :max caracteres.",
            'description.string' => "La descripción del perfil posee un formato no admitido.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $record = Profile::find($id);
        
        if(!$record){
            return response()->json(json_encode(['error' => ['El registro que intenta actualizar no existe.']]), 400);
        }

        $record->name = $request->name;
        $record->description = $request->description;
        $record->save();

        if($record){
            $this->activity->store("profiles", "UPDATE", $record->id, "Perfil Actualizado");

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
        
        $record = Profile::find($id);
        if($record){
            $delete = Profile::where('id', '=', $id)->delete();
    
            if($delete){
                $this->activity->store("profiles", "DELETE", $record->id, "Perfil Eliminado");

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
