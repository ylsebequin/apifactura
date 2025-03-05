<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $respuesta_exitosa = "Respuesta Exitosa";
    protected $respuesta_error = "Respuesta Incorrecta";

    public function index()
    {
        //return [1,2,3,4,];
        try {
            $cliente = Cliente::all();
            $respuesta = $this->get_response($this->respuesta_exitosa, 200, $cliente);
            return response()->json($respuesta, 200);
        } catch (Exception $e) {
            $respuesta = $this->get_response($e->getMessage(), 500, []);
            return response()->json($respuesta, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'apellido' => 'required|string|max:50',
                'ruc' => 'required|string|max:50',
                'razon_social' => 'nullable|string|max:100',
                'email' => 'nullable|string|email|max:500',
                'fecha_nacimiento' => 'nullable|string|max:100',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:255',

            ]);
            $response = Cliente::create($data);
            $respuesta = $this->get_response($this->respuesta_exitosa,200,$response);
            return response()->json($respuesta,200);

        } catch (Exception $e) {
            return response()->json($this->get_response($e->getMessage(),500,[]),503);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $respuesta = $this->get_response($this->respuesta_exitosa, 200, $cliente);
            return response()->json($respuesta);
        } catch (Exception $e) {

            return [
                "error" => true,
                "estado" => 500,
                "data" => $e->getMessage()

            ];
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
