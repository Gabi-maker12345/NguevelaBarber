<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = \App\Models\Service::all();
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric'
        ]);

        \App\Models\Service::create($data);
        return redirect()->route('services.index')->with('success', 'Serviço criado com sucesso!');
    }

    public function show(\App\Models\Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(\App\Models\Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric'
        ]);

        $service->update($data);
        return redirect()->route('services.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(\App\Models\Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Serviço removido com sucesso!');
    }
}
