<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorePegawaiRequest $request)
{
    $data = $request->validated();

    // Handle upload foto
    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('pegawai/foto', 'public');
    }

    // Handle upload KTP (bisa pdf atau image)
    if ($request->hasFile('ktp')) {
        $data['ktp'] = $request->file('ktp')->store('pegawai/ktp', 'public');
    }

    Pegawai::create($data);

    return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan!');
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
        //
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
