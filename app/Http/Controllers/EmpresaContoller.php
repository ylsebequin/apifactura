<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class EmpresaContoller extends Controller
{

    protected $respuesta_exitosa = "Respuesta Exitosa";
    protected $respuesta_error = "Ocurrio un error inesperado";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //throw new \Exception('error');
            $empresa = Empresa::all();
            $respuesta = $this->get_response($this->respuesta_exitosa, 200, $empresa);
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
        //validar los datos que el cliente nos esta enviando
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'ruc' => 'required|string',
                'razon_social' => 'required|string|max:100',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:255',

            ]);
            //aqui llamamos el modelo que en este caso es Empresa, esto hace conexion con la base de datos
            $response = Empresa::create($data);
            $respuesta = $this->get_response(
                $this->respuesta_exitosa,
                200,
                $response
            );
            return response()->json($respuesta, 200);
        } catch (Exception $e) {
            return response()->json($this->get_response($e->getMessage(), 500, []), 503);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $empresa = Empresa::findOrFail($id);
            $respuesta = $this->get_response(
                $this->respuesta_exitosa,
                200,
                $empresa
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
        try {

            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'ruc' => 'required|string',
                'razon_social' => 'required|string|max:100',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:255',

            ]);
            $empresa = Empresa::findOrFail($id);
            $empresa->update($data);
            return response()->json(
                $this->get_response(
                    $this->respuesta_exitosa,
                    200,
                    $empresa
                )
            );
        } catch (Exception $e) {
            return $this->get_response(
                $e->getMessage(),
                503,
                []
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $empresa = Empresa::findOrFail($id);
            $empresa->delete();

            return response()->json(
                $this->get_response(
                    "Se elimino correctamente el registro ".$id,
                    200,
                    $empresa
                )
            );
        } catch (Exception $e) {
            return $this->get_response($e->getMessage(), 503, []);
        }
    }
}
