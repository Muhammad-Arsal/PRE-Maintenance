<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Landlord;
use App\Models\Tenant;
use App\Models\PropertyType;;

class PropertyController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Properties';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Properties';
    
        $properties = Property::orderBy('created_at', 'desc')->paginate(10);

        $landlords = Landlord::all();
        $tenants = Tenant::all();
        $type = PropertyType::all();
    
        $keywords = "";
        $searchTenant = "";
        $searchLandlord = "";
        $searchType = "";
        $status = "";
        $searchBedrooms = "";
    
        return view('admin.properties.index', compact('page','keywords', 'properties','landlords', 'tenants', 'type', 'status', 'searchTenant', 'searchLandlord', 'searchType', 'searchBedrooms'));
    }

    public function create(){
        $page['page_title'] = 'Manage Properties';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Property';

        $landlords = Landlord::all();
        $tenants = Tenant::all();
        $property_types = PropertyType::all();
    
        return view('admin.properties.create', compact('page', 'tenants', 'landlords', 'property_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant' => 'required|integer',
            'monthly_rent' => 'required',
            'landlord' => 'required|integer',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $property = new Property();
        $property->tenant_id = $request->tenant;
        $property->landlord_id = $request->landlord;
        $property->type = $request->property_type;
        $property->management_charge = $request->management_charge;
        $property->bedrooms = $request->bedrooms;
        $property->has_garden = $request->has_garden;
        $property->has_garage = $request->has_garage;
        $property->has_parking = $request->has_parking;
        $property->is_active = $request->is_active;
        $property->is_furnished = $request->is_furnished;
        $property->rent_safe_month = $request->rent_safe_month;
        $property->monthly_rent = $request->monthly_rent;
        $property->number_of_floors = $request->number_of_floors;
        $property->note = $request->note;
        $property->status = $request->status;

        $property->line1 = $request->address_line_1;
        $property->line2 = $request->address_line_2;
        $property->line3 = $request->address_line_3;
        $property->city = $request->city;
        $property->county = $request->county;
        $property->postcode = $request->postal_code;

        $property->gas_certificate_due = $request->gas_certificate_due;
        $property->eicr_due = $request->eicr_due;
        $property->epc_due = $request->epc_due;
        $property->epc_rate = $request->epc_rate;

        if ($property->save()) {
            return redirect()
                ->route('admin.properties')
                ->withFlashMessage('Property created successfully!')
                ->withFlashType('success');
        } else {
            return back()->withFlashMessage('Failed to create property.')->withFlashType('danger');
        }
    }

    public function edit($id){
        $page['page_title'] = 'Manage Properties';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Property';

        $property = Property::find($id);
        $landlords = Landlord::all();
        $tenants = Tenant::all();
        $property_types = PropertyType::all();

        return view('admin.properties.edit', compact('page', 'property', 'tenants', 'landlords', 'property_types'));
    }

    public function update(Request $request, Property $id)
    {
        $property = $id;
        $request->validate([
            'tenant' => 'required|integer',
            'monthly_rent' => 'required',
            'landlord' => 'required|integer',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $property->tenant_id = $request->tenant;
        $property->landlord_id = $request->landlord;
        $property->type = $request->property_type;
        $property->management_charge = $request->management_charge;
        $property->bedrooms = $request->bedrooms;
        $property->has_garden = $request->has_garden;
        $property->has_garage = $request->has_garage;
        $property->has_parking = $request->has_parking;
        $property->is_active = $request->is_active;
        $property->is_furnished = $request->is_furnished;
        $property->rent_safe_month = $request->rent_safe_month;
        $property->monthly_rent = $request->monthly_rent;
        $property->number_of_floors = $request->number_of_floors;
        $property->note = $request->note;
        $property->status = $request->status;

        $property->line1 = $request->address_line_1;
        $property->line2 = $request->address_line_2;
        $property->line3 = $request->address_line_3;
        $property->city = $request->city;
        $property->county = $request->county;
        $property->postcode = $request->postal_code;

        $property->gas_certificate_due = $request->gas_certificate_due;
        $property->eicr_due = $request->eicr_due;
        $property->epc_due = $request->epc_due;
        $property->epc_rate = $request->epc_rate;

        if ($property->save()) {
            return redirect()
                ->route('admin.properties')
                ->withFlashMessage('Property updated successfully!')
                ->withFlashType('success');
        } else {
            return back()->withFlashMessage('Failed to update property.')->withFlashType('danger');
        }
    }

    public function destroy(Property $id)
    {
        if ($id->delete()) {
            return redirect()
                ->route('admin.properties')
                ->withFlashMessage('Property permanently deleted successfully!')
                ->withFlashType('success');
        } else {
            return back()->withFlashMessage('Failed to delete property.')->withFlashType('danger');
        }
    }

    public function show($id)
    {
        $property = Property::where('id', $id)->first();

        $tenant = \DB::table('tenants')->where('id', $property->tenant_id)->first();
        $landlord = \DB::table('landlords')->where('id', $property->landlord_id)->first();

        $page['page_title'] = 'View Property Details';

        return view('admin.properties.show', compact('page', 'property', 'tenant', 'landlord'));
    }

    public function searchData(Request $request)
    {
        $page['page_title'] = 'Manage Properties';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Properties';

        $status = $request->input('status');
        $searchTenant = $request->input('searchTenant');
        $searchLandlord = $request->input('searchLandlord');
        $searchType = $request->input('searchType');
        $searchBedrooms = $request->input('searchBedrooms');
        $keywords = $request->input('keywords');

        $query = Property::query();
        $landlords = Landlord::all();
        $tenants = Tenant::all();
        $type = PropertyType::all();

        if (!empty($status)) {
            $query->where('status', $status);
        }
        if (!empty($searchTenant)) {
            $query->where('tenant_id', $searchTenant);
        }
        if (!empty($searchLandlord)) {
            $query->where('landlord_id', $searchLandlord);
        }
        if (!empty($searchType)) {
            $query->where('type', $searchType);
        }
        if (!empty($searchBedrooms)) {
            $query->where('bedrooms', $searchBedrooms);
        }
        if (!empty($keywords)) {
            $query->where(function ($q) use ($keywords) {
                $q->where('line1', 'LIKE', "%{$keywords}%")
                ->orWhere('city', 'LIKE', "%{$keywords}%")
                ->orWhere('county', 'LIKE', "%{$keywords}%")
                ->orWhere('postcode', 'LIKE', "%{$keywords}%");
            });
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.properties.index', compact('page', 'properties', 'status', 'searchTenant', 'searchLandlord', 'searchType', 'searchBedrooms', 'keywords', 'landlords', 'tenants', 'type'));
    }


}
