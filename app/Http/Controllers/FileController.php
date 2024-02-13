<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
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
        return File::all();
    }

    
    /**
     * Display the specified resource.
     *
     * @param  id  $file
     * @return \Illuminate\Http\Response
     */
    public function show($origin_table, $origin_id)
    {
        $record = File::where("origin_table", $origin_table)->where('origin_id', $origin_id)->first();

        if($record){

            $url = sprintf("/%s/%s", $record->storage, $record->file);

            $this->activity->store("files", "SHOW", $record->id, "Archivo $record->file consultado.");

            return response()->json([
                'status' => 'success',
                'result' => $url
            ], 200);

        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'El Archivo, no existe.',
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file')->store('/', $request->storage);

        $record = new File();
        $record->storage = $request->storage;
        $record->origin_table = $request->origin_table;
        $record->origin_id = $request->origin_id;
        $record->file = $file;
        $record->name = $request->file('file')->getClientOriginalName();
        $record->description = $request->description;
        $record->save();

        if($record){
            $this->activity->store("files", "STORE", $record->id, "Archivo $record->id Cargado");
        }

        return response()->json([
            'status' => 'success',
            'result' => $record,
            'message' => 'Archivo cargado correctamente.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = File::find($id);

        if($record){
            $delete = File::where('id', $id)->delete();

            Storage::disk($record->storage)->delete($record->file);

            if($delete){
                $this->activity->store("files", "DELETE", $record->id, "Archivo $record->file Eliminado.");

                return response()->json([
                    'status' => 'success',
                    'message' => 'Archivo eliminado correctamente.',
                ], 200);
            }
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'El Archivo que intenta eliminar, no existe.',
            ], 200);
        }
        
        return response()->json(json_encode(['error' => ['El Archivo no pudo ser eliminado.']]), 400);
    }
}
