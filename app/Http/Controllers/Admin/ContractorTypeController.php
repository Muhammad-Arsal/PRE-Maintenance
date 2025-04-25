<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContractorType;

class ContractorTypeController extends Controller
{
    public function index()
    {
        $contractorTypes = ContractorType::all();
        return view('admin.settings.contractor-type.index', compact('contractorTypes'));
    }

    public function create()
    {
        return view('admin.settings.contractor-type.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        ContractorType::create($request->only('name'));
        return redirect()
        ->route('admin.settings.contractorType')
        ->withFlashMessage('contractor Type created successfully!')
        ->withFlashType('success');

    }

    public function edit(ContractorType $id)
    {
        return view('admin.settings.contractor-type.edit', compact('id'));
    }

    public function update(Request $request, ContractorType $id)
    {
        $request->validate(['name' => 'required']);
        $id->update($request->only('name'));

        return redirect()
            ->route('admin.settings.contractorType')
            ->withFlashMessage('contractor Type updated successfully!')
            ->withFlashType('success');
    }

    public function destroy(ContractorType $id)
    {
        $id->delete();

        return redirect()
            ->route('admin.settings.contractorType')
            ->withFlashMessage('contractor Type deleted successfully!')
            ->withFlashType('success');
    }
}
