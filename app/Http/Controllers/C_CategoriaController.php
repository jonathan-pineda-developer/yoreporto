<?php

namespace App\Http\Controllers;

use App\Models\C_Categoria;
use Illuminate\Http\Request;
use Validator;

class C_CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = C_Categoria::all();
        return $categorias;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $categoria = new C_Categoria();
        $categoria->id = $request->id;
        $categoria->descripcion = $request->descripcion; 
        $categoria->user_id = $request->user_id;

        request()->validate(C_Categoria::$rules);

        $categoria->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $categoria = C_Categoria::findOrFail($id);
        $categoria->descripcion = $request->descripcion; 
        $categoria->user_id = $request->user_id;

        request()->validate(C_Categoria::$rules);

        $categoria->save();

        return $categoria;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    
    public function destroy(Request $request, $id)
    {
        $categoria = C_Categoria::destroy($request->$id);
        return $categoria;
    }
    
}
