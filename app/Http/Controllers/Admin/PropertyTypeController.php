<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PropertyType;

class PropertyTypeController extends Controller
{
    public function index()
    {
        $propertyTypes = PropertyType::all();
        return view('admin.settings.property_types.index', compact('propertyTypes'));
    }

    public function create()
    {
        return view('admin.settings.property_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        PropertyType::create($request->only('name'));
        return redirect()
        ->route('admin.settings.propertyType')
        ->withFlashMessage('Property Type created successfully!')
        ->withFlashType('success');

    }

    public function edit(PropertyType $id)
    {
        return view('admin.settings.property_types.edit', compact('id'));
    }

    public function update(Request $request, PropertyType $id)
    {
        $request->validate(['name' => 'required']);
        $id->update($request->only('name'));

        return redirect()
            ->route('admin.settings.propertyType')
            ->withFlashMessage('Property Type updated successfully!')
            ->withFlashType('success');
    }

    public function destroy(PropertyType $id)
    {
        $id->delete();

        return redirect()
            ->route('admin.settings.propertyType')
            ->withFlashMessage('Property Type deleted successfully!')
            ->withFlashType('success');
    }
}
