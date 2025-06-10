<?php

namespace App\Http\Controllers\Tenant;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Tasks;
use App\Models\Events;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\EventType;
use App\Models\Contractor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralCorrespondenceCall;

class TenantCalendarController extends Controller
{
    public function index(Request $request, $tenantId) {
        $page['page_title'] = 'Diary';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Diary';

        $admin_id = Auth::guard('admin')->user()->id;
        $events = Events::whereHas('EventProperty.tenant', function($query) use ($tenantId) {
            $query->where('id', $tenantId);
        })->orderBy('created_at', 'desc')->get();
        // $tasks = Tasks::orderBy('created_at', 'desc')->where('is_critical', 1)->get();
        // $meetings = GeneralCorrespondenceCall::where('is_call', 'no')->get();
        
        $events = $events;
        
        $data = [];
        $color = '';
        $i = 1;
        foreach($events as $d) {
            $event_type = null;
            $property_name = null;
            
            

            if($d instanceof Events) {
               $property_id = \DB::table('event_property')
                    ->where('event_id', $d->id)
                    ->pluck('platform_user_id')
                    ->toArray();

                $properties = Property::whereIn('id', $property_id)->get();

                $property_name = $properties->map(function ($property) {
                    return implode(', ', [
                        $property->line1,
                        $property->city,
                        $property->county,
                        $property->country,
                        $property->postcode
                    ]);
                })->implode(' | ');


                $property_array = explode(",", $property_name);
                if(count($property_array) > 1 || count($property_array) == 0) {
                    $color = "#1d1e53";
                }


                $event_type = EventType::where('id', $d->event_type)->first();

                $date_from = Carbon::parse($d->date_from);
                $date_to = Carbon::parse($d->date_to);
                
                if ($date_from->equalTo($date_to)) {
                    $date_to = null;
                    $date_from = Carbon::parse($d->date_from);
                    $date_from = $date_from->toIso8601String();
                } else {
                    $date_from = Carbon::parse($d->date_from);
                    $date_from = $date_from->toIso8601String();
                    $date_to = Carbon::parse($d->date_to);
                    $date_to = $date_to->toIso8601String();
                }

                $url = route('tenant.diary.event.edit', [$d->id,$tenantId]);
            }

            $data[] = array(
                'toolTipTitle' => strlen($d->description) > 0 ? strip_tags($d->description) : '',
                'title' => strlen($d->description) > 25 ? substr(strip_tags($d->description), 0, 25) . "..." : strip_tags($d->description),
                'mobileTitle' => strlen($d->description) > 130 ? substr(strip_tags($d->description), 0, 130) . "..." : strip_tags($d->description),
                'event_type' => $event_type ? $event_type->event_name : '',
                'description' => $property_name,
                'start' => $date_from,
                'end' => $date_to,
                'url' => $url,
                'color' => $color
            );
        }


        $event_types = EventType::orderBy('event_name', 'asc')->get();
        $properties = Property::orderBy('created_at', 'asc')->get();
        $contacts = Contractor::orderBy('name', 'asc')->get();
        // $providers = Supplier::orderBy('name', 'asc')->get();

        if($request->get('savedState') == 'true') {
            $calendarDate = session('calendar_date', date('Y-m-d'));
            $calendarView = session('calendar_view', 'timeGridDay');
        } else {
            $calendarDate = date('Y-m-d');
            $calendarView = 'timeGridDay';
        }

        $tenant_id = $tenantId;

        return view('tenant.calendar.index', compact('page', 'data', 'event_types', 'properties', 'contacts', 'calendarDate', 'calendarView', 'tenant_id'));	
    }

    public function saveCalendarState(Request $request) {
        session([
            'calendar_date' => $request->date,
            'calendar_view' => $request->view,
        ]);

        return response()->json(['success' => true]);
    }

    public function edit(Events $id, $tenantId, $created_by_type = 'tenant') {
        $page['page_title'] = 'Event';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('tenant.dashboard');
        $page['page_current'] = 'Edit Event';


        $event = $id;
        $event->load([
            'event' => function ($query) {
                $query->orderBy('created_at', 'desc'); 
            },
            'docs' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->loadCount('events');

        $event_property = \DB::table('event_property')->where('event_id', $event->id)->get();
        $event_contacts = \DB::table('event_contacts')->where('event_id', $event->id)->get();
        // $event_providers = \DB::table('event_providers')->where('event_id', $event->id)->get();
        $event_types = EventType::orderBy('event_name', 'asc')->get();
        $properties = Property::orderBy('created_at', 'asc')->get();
        $contacts = Contractor::orderBy('name', 'asc')->get();
        // $providers = Supplier::orderby('name', 'desc')->get();
        $tenant_id = $tenantId;

        return view('tenant.calendar.event.edit', compact('page', 'event_types', 'properties', 'event', 'event_property', 'event_contacts', 'contacts', 'tenant_id'));
    }
}
