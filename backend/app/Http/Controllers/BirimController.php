<?php

namespace App\Http\Controllers;

use App\Models\Birim;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BirimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Birim::query()->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'birim_adi' => ['required', 'string', 'max:255'],
            'konumu' => ['nullable', 'string', 'max:255'],
        ]);

        $birim = Birim::create($data);

        return response()->json($birim, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Birim $birimler)
    {
        // Route-Model Binding: route key is {birimler}
        return response()->json($birimler);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Birim $birimler)
    {
        $data = $request->validate([
            'birim_adi' => ['sometimes', 'required', 'string', 'max:255'],
            'konumu' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $birimler->update($data);

        return response()->json($birimler);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Birim $birimler)
    {
        $birimler->delete();
        return response()->noContent();
    }
}
