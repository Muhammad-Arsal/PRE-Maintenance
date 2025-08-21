<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContractorType;

class ContractorTypeController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Contractor Types';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Contractor Types';
        $contractorTypes = ContractorType::all();
        return view('admin.settings.contractor-type.index', compact('contractorTypes', 'page'));
    }

    public function create()
    {
        $page['page_title'] = 'Create Contractor Type';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Create Contractor Type';   

        return view('admin.settings.contractor-type.create', compact('page'));
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
        $page['page_title'] = 'Edit Contractor Type';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');   
        $page['page_current'] = 'Edit Contractor Type';
        return view('admin.settings.contractor-type.edit', compact('id', 'page'));
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
