<?php

namespace App\Http\Controllers;

use App\Models\Personel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PersonelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Personel::query()->with(['unvan', 'birim'])->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'adi_soyadi' => ['required', 'string', 'max:255'],
            'unvan_id' => ['required', 'integer', 'exists:unvanlar,id'],
            'birim_id' => ['required', 'integer', 'exists:birimler,id'],
            'notlar' => ['nullable', 'string'],
        ]);

        $personel = Personel::create($data);

        return response()->json($personel->load(['unvan', 'birim']), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Personel $personel)
    {
        return response()->json($personel->load(['unvan', 'birim']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personel $personel)
    {
        $data = $request->validate([
            'adi_soyadi' => ['sometimes', 'required', 'string', 'max:255'],
            'unvan_id' => ['sometimes', 'required', 'integer', 'exists:unvanlar,id'],
            'birim_id' => ['sometimes', 'required', 'integer', 'exists:birimler,id'],
            'notlar' => ['sometimes', 'nullable', 'string'],
        ]);

        $personel->update($data);

        return response()->json($personel->load(['unvan', 'birim']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personel $personel)
    {
        $personel->delete();
        return response()->noContent();
    }
}
