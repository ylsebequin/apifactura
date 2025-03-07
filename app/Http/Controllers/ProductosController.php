<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use Exception;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $respuesta_exitosa = "Respuesta Exitosa";
    protected $respuesta_error = "Ocurrio un error inesperado";


    public function index()
    {
        try{
            
            $productos = Productos::all();
            $respuesta = $this->get_response($this->respuesta_exitosa, 200, $productos);
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
        $data = $request->validate([

            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
//creamos una condicion para que se guarde en el request mediante el metodo file la imagen
        if ($request->hasFile('imagen')){
$data ['imagen'] = $request->file('imagen')->store('public/productos');

        }
        $response = Productos::create($data);
        return response()->json($this->get_response("Creado Correctamente",200,$response),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $productos = Productos::findOrFail($id);
            $respuesta = $this->get_response(
                $this->respuesta_exitosa,
                200,
                $productos
            );

            return response()->json($respuesta);
        } catch (Exception $e) {

            return [
                "error" => true,
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
