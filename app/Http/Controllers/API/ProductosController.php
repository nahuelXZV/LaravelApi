<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\productos;
use App\Http\Resources\Producto as ProductoResource;

class ProductosController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = productos::all();
        return $this->sendResponse(ProductoResource::collection($productos), 'Productos encontrados.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'quantity' => 'required',
            'price' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $producto = productos::create($input);
        return $this->sendResponse(new ProductoResource($producto), 'Producto Creado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = productos::find($id);
        if (is_null($producto)) {
            return $this->sendError('Producto does not exist.');
        }
        return $this->sendResponse(new ProductoResource($producto), 'Producto encontrado.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, productos $producto)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $producto->name = $input['name'];
        $producto->quantity = $input['quantity'];
        $producto->price = $input['price'];
        $producto->save();

        return $this->sendResponse(new ProductoResource($producto), 'producto updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(productos $producto)
    {
        $producto->delete();
        return $this->sendResponse([], 'Producto Borrado.');
    }

    /***
     * Search the products
     */

    public function search(Request $request)
    {
        $productos = productos::where($request->attribute, 'like',  '%' . $request->search . '%')
            ->orderBy($request->attribute, $request->direction)
            ->get();
        return $this->sendResponse(ProductoResource::collection($productos), 'Productos encontrados.');
    }
}
