<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoices;
use App\Models\Property;
use Carbon\Carbon;

class InvoicesController extends Controller
{
    public function index(){
        $page['page_title'] = 'Manage Account';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Invoices';

        $invoices = Invoices::paginate(10);
        $keywords = '';

        return view('admin.invoices.index', compact('page', 'invoices', 'keywords'));
    }

    public function create(){
        $page['page_title'] = 'Manage Account';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Invoice';

        $properties = Property::all();

        return view('admin.invoices.create', compact('page','properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'subtotal' => 'required',
            'vat_rate' => 'required',
            'vat' => 'required',
            'total' => 'required',
            'description' => 'required|string|max:1000',
            'address_option' => 'required|in:entered,property,landlord',
        ], [
            'property.required' => 'The property field is required.',
            'property.exists' => 'The selected property is invalid.',
            'date.required' => 'The date field is required.',
            'date.date_format' => 'The date format must be DD/MM/YYYY.',
            'subtotal.required' => 'The subtotal is required.',
            'subtotal.numeric' => 'Subtotal must be a number.',
            'vat_rate.numeric' => 'VAT rate must be a number.',
            'vat.numeric' => 'VAT must be a number.',
            'total.required' => 'Total is required.',
            'total.numeric' => 'Total must be a number.',
            'description.required' => 'Description is required.',
            'address_option.required' => 'Please select an address option.',
        ]);

        $formattedDate = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');


        $invoice = new Invoices();
        $invoice->property_id = $request->property;
        $invoice->date = $formattedDate;
        $invoice->reference = $request->reference;

        $invoice->subtotal = $request->subtotal;
        $invoice->vat_rate = $request->vat_rate;
        $invoice->vat = $request->vat;
        $invoice->total = $request->total;
        $invoice->vat_applicable = $request->vat_applicable;
        
        
        $invoice->address_option = $request->address_option;
        $invoice->line1 =  $request->address_line_1 ?? null;
        $invoice->line2 =  $request->address_line_2 ?? null;
        $invoice->line3 =  $request->address_line_3 ?? null;
        $invoice->county =  $request->county ?? null;
        $invoice->city =  $request->city ?? null;
        $invoice->postcode =  $request->postal_code ?? null;
        $invoice->country =  $request->country ?? null;

        $invoice->description = $request->description;

        if($request->address_option == 'property')
        {
            $invoice->property_address_id = $request->property;
        }else if($request->address_option == 'landlord')
        {
            $property = Property::where('id',$request->property)->with('landlord')->first();
            
            $invoice->landlord_address_id = $property->landlord->id;
        }
        if ($invoice->save()) {
            return redirect()
                ->route('admin.invoices')
                ->withFlashMessage('Invoice Saved Successfully')
                ->withFlashType('success');
        }
    }

    public function edit($id){
        $page['page_title'] = 'Manage Account';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Invoice';

        $invoice = Invoices::where('id', $id)->with('property')->first();
        $properties = Property::all();

        return view('admin.invoices.edit', compact('page', 'invoice', 'properties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'property' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'subtotal' => 'required',
            'vat_rate' => 'required',
            'vat' => 'required',
            'total' => 'required',
            'description' => 'required|string|max:1000',
            'address_option' => 'required|in:entered,property,landlord',
        ], [
            'property.required' => 'The property field is required.',
            'property.exists' => 'The selected property is invalid.',
            'date.required' => 'The date field is required.',
            'date.date_format' => 'The date format must be DD/MM/YYYY.',
            'subtotal.required' => 'The subtotal is required.',
            'subtotal.numeric' => 'Subtotal must be a number.',
        ]);
        $formattedDate = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        $invoice = Invoices::find($id);

        $invoice->property_id = $request->property;
        $invoice->date = $formattedDate;
        $invoice->reference = $request->reference;

        $invoice->subtotal = $request->subtotal;
        $invoice->vat_rate = $request->vat_rate;
        $invoice->vat = $request->vat;
        $invoice->total = $request->total;
        $invoice->vat_applicable = $request->vat_applicable;

        $invoice->address_option = $request->address_option;
        $invoice->line1 =  $request->address_line_1 ?? null;
        $invoice->line2 =  $request->address_line_2 ?? null;
        $invoice->line3 =  $request->address_line_3 ?? null;
        $invoice->county =  $request->county ?? null;
        $invoice->city =  $request->city ?? null;
        $invoice->postcode =  $request->postal_code ?? null;
        $invoice->country =  $request->country ?? null;

        $invoice->description = $request->description;

        if($request->address_option == 'property')
        {
            $invoice->property_address_id = $request->property;
        }else if($request->address_option == 'landlord')
        {
            $property = Property::where('id',$request->property)->with('landlord')->first();

            $invoice->landlord_address_id = $property->landlord->id;
        }
        if ($invoice->save()) {
            return redirect()
                ->route('admin.invoices')
                ->withFlashMessage('Invoice Updated Successfully')
                ->withFlashType('success');
        }
    }

    public function destroy($id)
    {
        $invoice = Invoices::find($id);
        if ($invoice->delete()) {
            return redirect()
                ->route('admin.invoices')
                ->withFlashMessage('Invoice Deleted Successfully')
                ->withFlashType('success');
        }
    }

    public function show($id)
    {
        $page['page_title'] = 'Manage Account';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'View Invoice';

        $invoice = Invoices::where('id', $id)->with('property')->first();

        return view('admin.invoices.show', compact('page', 'invoice'));
    }

    public function searchData(Request $request)
    {
        $page['page_title'] = 'Manage Account';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Invoice';

        $keywords = $request['keywords'];

        $query = Invoices::with(['property'])->orderBy('created_at', 'asc');
        
        if ($keywords) {
            $query->whereHas('property', function ($q) use ($keywords) {
                $q->where('line1', 'like', '%' . $keywords . '%')
                  ->orWhere('city', 'like', '%' . $keywords . '%')
                  ->orWhere('county', 'like', '%' . $keywords . '%')
                  ->orWhere('postcode', 'like', '%' . $keywords . '%');
            });
        }             

        $invoices = $query->orderBy('created_at', 'asc')->paginate(10);

        return view('admin.invoices.index', compact('page', 'invoices', 'keywords'));
    }

    public function getAddressDetails(Request $request)
    {
        $property = Property::find($request->property_id)->with('landlord')->first();

        if (!$property) {
            return response()->json(['success' => false, 'message' => 'Property not found'], 404);
        }

        if ($request->address_type === 'property') {
            $address = [
                'address_line_1' => $property->line1,
                'address_line_2' => $property->line2,
                'address_line_3' => $property->line3,
                'city' => $property->city,
                'county' => $property->county,
                'postal_code' => $property->postcode,
                'country' => $property->country,
            ];
        } elseif ($request->address_type === 'landlord') {
            $landlord = $property->landlord;

            if (!$landlord) {
                return response()->json(['success' => false, 'message' => 'Landlord not found'], 404);
            }

            $address = [
                'address_line_1' => $landlord->line1,
                'address_line_2' => $landlord->line2,
                'address_line_3' => $landlord->line3,
                'city' => $landlord->city,
                'county' => $landlord->county,
                'postal_code' => $landlord->postcode,
                'country' => $landlord->country,
            ];
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid address type'], 400);
        }

        return response()->json(['success' => true, 'data' => $address]);
    }

}

