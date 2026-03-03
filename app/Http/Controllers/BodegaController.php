<?php

namespace App\Http\Controllers;

use App\Models\Bodega;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBodegaRequest;
use App\Http\Requests\UpdateBodegaRequest;

class BodegaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bodegas = Bodega::paginate(10);
        return view('bodegas.index', compact('bodegas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bodegas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBodegaRequest $request)
    {
        Bodega::create($request->validated());

        return redirect()->route('bodegas.index')->with('success', 'Bodega creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bodega = Bodega::findOrFail($id);
        return view('bodegas.edit', compact('bodega'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBodegaRequest $request, string $id)
    {
        $bodega = Bodega::findOrFail($id);

        $data = $request->validated();

        if ($id == 1) {
            unset($data['inhabilitado']);
        }

        $bodega->update($data);

        return redirect()->route('bodegas.index')->with('success', 'Bodega actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
