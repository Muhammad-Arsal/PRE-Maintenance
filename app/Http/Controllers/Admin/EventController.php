<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Events;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\EventDocs;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Services\EventService;
use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Observers\RecurrenceObserver;
use Illuminate\Support\Facades\Event;

class EventController extends Controller
{

    public function create() {
        $page['page_title'] = 'Event';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Event';

        $event_types = EventType::orderBy('event_name', 'asc')->get();
        $properties = Property::orderBy('created_at', 'asc')->get();
        $contacts = Contractor::orderBy('name', 'asc')->get();

        return view('admin.calendar.event.add', compact('page', 'event_types', 'properties', 'contacts'));
    }

    public function store(Request $request, EventService $eventService) {
        $request->validate([
            'docs.*' => 'nullable|file|max:51200'
        ]);

        $requestData = $request->only(['event_type', 'external_user', 'external_user_name', 'cc', 'address_main_contact', 'date', 'date_to', 'time_from', 'time_to', 'description', 'recurrence', 'reminder', 'repeated_for']);
        $property = $request->property ? $request->property : array();
        $contacts = $request->contacts ? $request->contacts : array();
        $files = $request->file('docs', []);

        $requestData['repeated_for'] = $request->repeated_for ? $request->repeated_for : '4';
        
        $event = $eventService->createEvent($requestData, $property, $contacts, $files, $tenantId = null, $created_by_type = 'admin');

        return redirect()
        ->route( 'admin.diary', ['savedState' => 'true'])
        ->withFlashMessage( 'Event created successfully!' )
        ->withFlashType( 'success' );
    }

    public function edit(Events $id) {
        $page['page_title'] = 'Event';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
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

        return view('admin.calendar.event.edit', compact('page', 'event_types', 'properties', 'event', 'event_property', 'event_contacts', 'contacts'));
    }

    public function update(Request $request, Events $id) {
        $request->validate([
            'docs.*' => 'nullable|file|max:51200'
        ]);
        
        $eventService = new EventService();
        $requestData = $request->only([
            'event_type', 'date', 'time_from', 'time_to', 'external_user_name', 'cc', 'address_main_contact',
            'description', 'comment', 'property', 'recurrence', 'date_to', 'contacts', 'repeated_for', 'apply_to_future'
        ]);

        $requestData['property'] = !empty($requestData['property']) ? $requestData['property'] : array();
        $requestData['contacts'] = !empty($requestData['contacts']) ? $requestData['contacts'] : array();
        $requestData['files'] = $request->file('docs', []);
        $requestData['repeated_for'] = $request->repeated_for ? $request->repeated_for : '4';

        $event = $id;
    
        $eventService->updateEvent($event, $requestData, $tenantId = null, $created_by_type = 'admin');  // Call the service method to update the event
    
        return redirect()
            ->route( 'admin.diary', ['savedState' => 'true'])
            ->withFlashMessage('Event updated successfully!')
            ->withFlashType('success');
    }

    public function fileDelete(EventDocs $id)
    {

        $eventDocs = $id;

        $filePath = public_path("events/event-{$eventDocs->event_id}/{$eventDocs->file_name}");

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        \DB::table('event_docs')->where('id', $eventDocs->id)->delete();

        return redirect()
        ->route('admin.diary.event.edit', $eventDocs->event_id)
        ->withFlashMessage('File deleted successfully!')
        ->withFlashType('success');
    }

    public function destroy(Events $id) {
        $event = $id;

        \DB::table('event_property')->where('event_id', $event->id)->delete();
        $event->delete();

        return redirect()
        ->route( 'admin.diary', ['savedState' => 'true'])
        ->withFlashMessage( 'Event deleted successfully!' )
        ->withFlashType( 'success' );
    }

    public function deleteAllRecurrences(Events $id) {
        $event = $id;

        if (is_null($event->event_id)) {
            Events::where('event_id', $event->id)->each(function ($childEvent) {
                \DB::table('event_property')->where('event_id', $childEvent->id)->delete();
                $childEvent->delete();
            });
    
            \DB::table('event_property')->where('event_id', $event->id)->delete();
            $event->delete();
        } else {
            $parentEvent = Events::where('id', $event->event_id)->first();
    
            if ($parentEvent) {
                Events::where('event_id', $parentEvent->id)->each(function ($childEvent) {
                    \DB::table('event_property')->where('event_id', $childEvent->id)->delete();
                    $childEvent->delete();
                });
    
                \DB::table('event_property')->where('event_id', $parentEvent->id)->delete();
                $parentEvent->delete();
            } else {
                Events::where('event_id', $event->event_id)->each(function ($childEvent) {
                    \DB::table('event_property')->where('event_id', $childEvent->id)->delete();
                    $childEvent->delete();
                });
            }
        }

        return redirect()
        ->route( 'admin.diary', ['savedState' => 'true'])
        ->withFlashMessage( 'All Recurrences deleted successfully!' )
        ->withFlashType( 'success' );
    }


    // Event Type
    public function manageType(){
        $page['page_title'] = 'Manage Event Type';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Event Type';

        $event_types = EventType::withTrashed()->orderBy('event_name', 'desc')->get();

        return view('admin.settings.event-type.index', compact('page', 'event_types'));
    }

    public function createType() {
        $page['page_title'] = 'Manage Event Type';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Event Type';

        $emailTemplates = EmailTemplate::orderBy('type', 'desc')->where('status', '1')->get();

        return view('admin.settings.event-type.add', compact('page', 'emailTemplates'));
    }

    public function storeType(Request $request) {
        $request->validate([
            'event_name' => 'required',
            'email_template' => 'required',
        ]);

        EventType::create([
            'event_name' => $request->event_name,
            'email_template_id' => $request->email_template
        ]);

        return redirect()
        ->route( 'admin.settings.event-type' )
        ->withFlashMessage( 'Event Type added successfully!' )
        ->withFlashType( 'success' );
    }

    public function editType($id) {
        $page['page_title'] = 'Manage Event Type';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Event Type';

        $event_type = EventType::where('id', $id)->with('emailTemplate')->withTrashed()->first();

        $emailTemplates = EmailTemplate::orderBy('type', 'desc')->where('status', '1')->get();

        return view('admin.settings.event-type.edit', compact('page', 'event_type', 'emailTemplates'));
    }

    public function updateType(Request $request, $id) {
        $request->validate([
            'event_name' => 'required',
            'email_template' => 'required',
        ]);

        $event_type = EventType::where('id', $id)->withTrashed()->first();

        EventType::where('id', $event_type->id)->withTrashed()->update([
            'event_name' => $request->event_name,
            'email_template_id' => $request->email_template
        ]);

        return redirect()
        ->route( 'admin.settings.event-type' )
        ->withFlashMessage( 'Event Type updated successfully!' )
        ->withFlashType( 'success' );
    }

    public function destroyType($id) {
        $event_type = EventType::where('id', $id)->withTrashed()->first();

        if($event_type->trashed()) {
            $event_type->restore();
        } else {
            $event_type->delete();
        }

        return redirect()
        ->route( 'admin.settings.event-type' )
        ->withFlashMessage( 'Event Type deleted successfully!' )
        ->withFlashType( 'success' );
    }
}
