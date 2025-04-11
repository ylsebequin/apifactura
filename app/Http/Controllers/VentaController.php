<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ventas = Venta::with('cliente', 'detalles.producto')->get();
            return response()->json($this->get_response("Listado de Ventas", 200, $ventas), 200);
        } catch (Exception $e) {
            return response()->json($this->get_response($e->getMessage(), 500, null), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            //CODIGO DE CREACION DE VENTA
            $data = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'fecha_venta' => 'required|date',
                'productos' => 'required|array',
                'productos.*.producto_id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
            ]);

            $total = 0;

            $venta = Venta::create([
                'cliente_id' => $data['cliente_id'],
                'fecha_venta' => $data['fecha_venta'],
                'total_venta' => $total,
                'estado' => 'pendiente',
            ]);

            foreach ($data['productos'] as $producto) {

                $productoInfo = Productos::find($producto['producto_id']);
                if ($productoInfo->stock < $producto['cantidad']) {
                    return response()->json($this->get_response("No hay suficiente stock para el producto", 500, null), 500);
                }

                $subtotal = $productoInfo->precio * $producto['cantidad'];

                $total += $subtotal;

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto['producto_id'],
                    'cantidad' => $producto['cantidad'],
                    'precio' => $productoInfo->precio,
                    'subtotal' => $subtotal,
                ]);

                $productoInfo->update([
                    'stock' => $productoInfo->stock - $producto['cantidad']
                ]);
            }

            $venta->update([
                'total_venta' => $total,
                'estado' => 'pagada'
            ]);

            DB::commit();

            return response()->json($this->get_response("Venta realizada con exito", 200, $venta), 200);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json($this->get_response($e->getMessage(), 500, null), 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        try {
            $respuesta = $venta->with('detalles.producto')->first();
            return response()->json($this->get_response("Se recupero la venta", 200, $respuesta), 200);
        } catch (Exception $e) {
            return response()->json($this->get_response($e->getMessage(), 500, null), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        try {

            DB::beginTransaction();
            if ($venta->estado == 'pagada') {
                return response()->json($this->get_response("La venta ya fue pagada, no se puede modificar", 500, null), 500);
            }

            $data = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'fecha_venta' => 'required|date',
                'productos' => 'required|array',
                'productos.*.producto_id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
            ]);

            foreach ($venta->detalles as $detalle) {
                $productoInfo = Productos::find($detalle->producto_id);
                $productoInfo->update([
                    'stock' => $productoInfo->stock + $detalle->cantidad
                ]);
            }

            $venta->detalles()->delete();

            $total = 0;

            foreach ($data['productos'] as $producto) {

                $productoInfo = Productos::find($producto['producto_id']);

                if ($productoInfo->stock < $producto['cantidad']) {
                    return response()->json($this->get_response("No hay suficiente stock para el producto", 500, null), 500);
                }

                $subtotal = $productoInfo->precio * $producto['cantidad'];

                $total += $subtotal;

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto['producto_id'],
                    'cantidad' => $producto['cantidad'],
                    'precio' => $productoInfo->precio,
                    'subtotal' => $subtotal,
                ]);

                $productoInfo->update([
                    'stock' => $productoInfo->stock - $producto['cantidad']
                ]);
            }

            $venta->update([
                'total_venta' => $total,
                'estado' => 'pagada'
            ]);

            DB::commit();

            return response()->json($this->get_response("Venta actualizada con exito", 200, $venta), 200);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json($this->get_response($e->getMessage(), 500, null), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        //
    }
}
