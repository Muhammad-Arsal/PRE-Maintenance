<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Tasks;
use App\Models\Events;
use App\Models\Property;
use App\Models\EventType;
use App\Models\Contractor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralCorrespondenceCall;

class ContractorCalendarController extends Controller
{
    public function index(Request $request, $contractorId) {
        $page['page_title'] = 'Diary';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Diary';

        $admin_id = Auth::guard('admin')->user()->id;
        $events = Events::whereHas('EventContacts', function($query) use ($contractorId) {
            $query->where('contractors.id', $contractorId);
        })->orderBy('created_at', 'desc')->get();
        $tasks = Tasks::orderBy('created_at', 'desc')->where('is_critical', 1)->get();
        $meetings = GeneralCorrespondenceCall::where('is_call', 'no')->get();
        
        $events = $events->concat($tasks)->concat($meetings);
        
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

                $url = route('admin.contractors.diary.event.edit', [$d->id,$contractorId]);
            } else if($d instanceof Tasks) {
                $date = Carbon::createFromFormat('Y-m-d', $d->due_date)->startOfDay();                

                $date_from = $date->format('Y-m-d H:i:s');

                // $date_from = Carbon::parse($d->date_from);
                // $date_from = $date_from->toIso8601String();

                $date_to = null;

                $url = route('admin.tasks.edit', $d->id);
            } else if($d instanceof GeneralCorrespondenceCall) {
                $datetime = Carbon::createFromFormat('Y-m-d H:i', "$d->date $d->time");
                $date_from = $datetime->format('Y-m-d H:i:s');

                $date_to = null;
                if($d->time_to) {
                    $datetime_to = Carbon::createFromFormat('Y-m-d H:i', "$d->date $d->time_to");
                    $date_to = $datetime_to->format('Y-m-d H:i:s');   
                }             

                $url = route('admin.diary.meeting.editForm', ['id' => $d->id, 'type' => 'provider']);
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
        $contacts = Contractor::orderBy('name', 'asc')->where('id', $contractorId)->get();
        // $providers = Supplier::orderBy('name', 'asc')->get();

        if($request->get('savedState') == 'true') {
            $calendarDate = session('calendar_date', date('Y-m-d'));
            $calendarView = session('calendar_view', 'timeGridDay');
        } else {
            $calendarDate = date('Y-m-d');
            $calendarView = 'timeGridDay';
        }

        $contractor_id = $contractorId;

        return view('admin.contractors.calendar.index', compact('page', 'data', 'event_types', 'properties', 'contacts', 'calendarDate', 'calendarView', 'contractor_id'));	
    }

    // public function editMeetingForm($id, $type) {
    //     $page['page_title'] = 'Meeting';
    //     $page['page_parent'] = 'Home';
    //     $page['page_parent_link'] = route('admin.dashboard');
    //     $page['page_current'] = 'Edit Meeting';

    //     $meeting = GeneralCorrespondenceCall::where('id', $id)->first();
    //     $contacts = contractor::orderBy('name', 'asc')->get();

    //     return view('admin.calendar.edit_meeting', compact('page', 'meeting', 'contacts'));
    // }

    // public function storeMeetingForm(Request $request, $id, $type) {
    //     $request->validate([
    //         'meeting_date' => 'required',
    //         'meeting_time' => 'required',
    //         'meeting_time_to' => 'required',
    //     ], [
    //         'meeting_time.required' => "This field is required",
    //         'meeting_time_to.required' => "This field is required",
    //     ]);

    //     $data = $request->except('_token');
    //     if($data['meeting_date']) {
    //         $meeting_date = Carbon::createFromFormat("d/m/Y", $data['meeting_date'])
    //         ->format('Y-m-d H:i:s');
    //     }

    //     $meeting = GeneralCorrespondenceCall::where('id', $id)->update([
    //         'description' => $data['meeting_notes'],
    //         'time' => $data['meeting_time'],
    //         'time_to' => $data['meeting_time_to'],
    //         'date' => $meeting_date,
    //         'call_type'   => null,
    //         'is_call' => 'no',
    //         'contractor_id' => $request->contacts,
    //     ]);

    //     return redirect()
    //     ->route( 'admin.contractors.calendar' )
    //     ->withFlashMessage( 'Meeting updated successfully!' )
    //     ->withFlashType( 'success' );
    // }

    public function saveCalendarState(Request $request) {
        session([
            'calendar_date' => $request->date,
            'calendar_view' => $request->view,
        ]);

        return response()->json(['success' => true]);
    }
}