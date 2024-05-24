<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Functions;

use Illuminate\Support\Facades\Validator;

class FunctionController extends Controller
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
        return Functions::all();
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
            'url' => 'required|string|max:100|unique:functions',
        ],[
            'name.required' => "El Nombre de la función no puede estar vacío.",
            'name.string' => "El Nombre de la función posee un formato no admitido.",
            'name.between' => "El Nombre de la función debe contener entre :min - :max caracteres.",
            'description.string' => "La descripción de la función posee un formato no admitido.",
            'url.required' => "La URL de la función no puede estar vacía.",
            'url.string' => "La URL de la función posee un formato no admitido.",
            'url.max' => "La URL de la función debe contener como maximo :max caracteres.",
            'url.unique' => "La URL de la función ya se encuentra registrada.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $record = new Functions();
        $record->name = $request->name;
        $record->description = $request->description;
        $record->url = strtolower(addslashes(trim($request->url)));
        $record->save();

        if($record){
            $this->activity->store("functions", "STORE", $record->id, "Función $record->id Creada");

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
        $record = Functions::find($id);

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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,75',
            'description' => 'nullable|string',
            'url' => 'required|string|max:100',
        ],[
            'name.required' => "El Nombre de la función no puede estar vacío.",
            'name.string' => "El Nombre de la función posee un formato no admitido.",
            'name.between' => "El Nombre de la función debe contener entre :min - :max caracteres.",
            'description.string' => "La descripción de la función posee un formato no admitido.",
            'url.required' => "La URL de la función no puede estar vacía.",
            'url.string' => "La URL de la función posee un formato no admitido.",
            'url.max' => "La URL de la función debe contener como maximo :max caracteres.",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $record = Functions::find($id);

        if(!$record){
            return response()->json(json_encode(['error' => ['El registro que intenta actualizar no existe.']]), 400);
        }

        $record->name = $request->name;
        $record->description = $request->description;
        $record->url = strtolower(addslashes(trim($request->url)));
        $record->save();

        if($record){
            $this->activity->store("functions", "UPDATE", $record->id, "Función $record->id Actualizada");

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
        $record = Functions::find($id);

        if($record){
            $delete = Functions::where('id', $id)->delete();
    
            if($delete){
                $this->activity->store("functions", "DELETE", $record->id, "Función $record->id Eliminada");

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
