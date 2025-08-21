<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PropertyType;

class PropertyTypeController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Properties Types';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Properties Types';

        $propertyTypes = PropertyType::all();
        return view('admin.settings.property_types.index', compact('propertyTypes', 'page'));
    }

    public function create()
    {
        $page['page_title'] = 'Manage Properties Types';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');   
        $page['page_current'] = 'Create Property Type';

        return view('admin.settings.property_types.create', compact('page'));
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
        $page['page_title'] = 'Manage Properties Types';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Property Type';
        return view('admin.settings.property_types.edit', compact('id', 'page'));
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
