<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GeneralCorrespondenceCall;
use App\Models\Events;
use App\Models\EventType;
use App\Models\Tasks;
use App\Models\Landlord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantCalendarController extends Controller
{
    public function index(Request $request, $tenantId) {
        $page['page_title'] = 'Diary';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Diary';

        $admin_id = Auth::guard('admin')->user()->id;
        $events = Events::orderBy('created_at', 'desc')->where('created_by', $tenantId)->where('created_by_type', 'tenant')->get();
        // $tasks = Tasks::orderBy('created_at', 'desc')->where('is_critical', 1)->get();
        // $meetings = GeneralCorrespondenceCall::where('is_call', 'no')->get();
        
        // $events = $events->concat($tasks)->concat($meetings);
        
        $data = [];
        $color = '';
        $i = 1;
        foreach($events as $d) {
            $event_type = null;
            $platform_user = null;
            
            

            if($d instanceof Events) {
                $platform_users_ids = \DB::table('event_users')->where('event_id', $d->id)->pluck('platform_user_id')->toArray();

                $platform_users = Admin::whereIn('id', $platform_users_ids)->get();
                
                $platform_user = $platform_users->pluck('name')->implode(', ');

                $platform_user_array = explode(",", $platform_user);
                if(count($platform_user_array) > 1 || count($platform_user_array) == 0) {
                    $color = "#1d1e53";
                } else {
                    if($platform_user == 'Jon Bucknall') {
                        $color = "#424443";
                    } else if ($platform_user == 'Nick Segal') {
                        $color = "#fd7e14";
                    } else {
                        $color = "#1d1e53";
                    }
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

                $url = route('admin.tenants.diary.event.edit', [$d->id, $tenantId]);
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
                'description' => $platform_user,
                'start' => $date_from,
                'end' => $date_to,
                'url' => $url,
                'color' => $color
            );
        }


        $event_types = EventType::orderBy('event_name', 'asc')->get();
        $platform_users = Admin::orderBy('name', 'asc')->get();
        $contacts = Landlord::orderBy('name', 'asc')->get();
        // $providers = Supplier::orderBy('name', 'asc')->get();

        if($request->get('savedState') == 'true') {
            $calendarDate = session('calendar_date', date('Y-m-d'));
            $calendarView = session('calendar_view', 'timeGridDay');
        } else {
            $calendarDate = date('Y-m-d');
            $calendarView = 'timeGridDay';
        }

        $tenant_id = $tenantId;

        return view('admin.tenants.calendar.index', compact('page', 'data', 'event_types', 'platform_users', 'contacts', 'calendarDate', 'calendarView', 'tenant_id'));
    }

    // public function editMeetingForm($id, $type) {
    //     $page['page_title'] = 'Meeting';
    //     $page['page_parent'] = 'Home';
    //     $page['page_parent_link'] = route('admin.dashboard');
    //     $page['page_current'] = 'Edit Meeting';

    //     $meeting = GeneralCorrespondenceCall::where('id', $id)->first();
    //     $contacts = Landlord::orderBy('name', 'asc')->get();

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
    //         'landlord_id' => $request->contacts,
    //     ]);

    //     return redirect()
    //     ->route( 'admin.tenants.calendar' )
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
