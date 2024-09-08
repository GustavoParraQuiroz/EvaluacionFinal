<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProyectoController extends Controller

{
    public function index()
    {
        $proyectos = Proyecto::all();

        if ($proyectos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron proyectos'], 404);
        }

        return response()->json(['proyectos' => $proyectos], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:activo,inactivo',
            'responsable' => 'required|max:255',
            'monto' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación', 'errors' => $validator->errors()], 400);
        }

        $proyecto = Proyecto::create($request->all());

        return response()->json(['message' => 'Proyecto creado con éxito', 'proyecto' => $proyecto], 201);
    }

    public function show($id)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        return response()->json(['proyecto' => $proyecto], 200);
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:activo,inactivo',
            'responsable' => 'required|max:255',
            'monto' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación', 'errors' => $validator->errors()], 400);
        }

        $proyecto->update($request->all());

        return response()->json(['message' => 'Proyecto actualizado con éxito', 'proyecto' => $proyecto], 200);
    }

    public function destroy($id)
    {
        $proyecto = Proyecto::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        $proyecto->delete();

        return response()->json(['message' => 'Proyecto eliminado con éxito'], 204);
    }
}