<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $service = Service::create($data);

        if ($request->expectsJson()) {
            return response()->json($service, 201);
        }

        return redirect()->route('services.index')->with('success', 'Serviço criado com sucesso!');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $service->update($data);

        if ($request->expectsJson()) {
            return response()->json($service->fresh());
        }

        return redirect()->route('services.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        if (request()->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('services.index')->with('success', 'Serviço removido com sucesso!');
    }
}
