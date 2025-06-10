<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Events;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\EventDocs;
use App\Models\EventType;
use App\Models\Contractor;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Services\EventService;
use App\Http\Controllers\Controller;
use App\Observers\RecurrenceObserver;
use Illuminate\Support\Facades\Event;

class PropertyEventController extends Controller
{

    public function create($propertyId) {
        $page['page_title'] = 'Event';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Event';

        $event_types = EventType::orderBy('event_name', 'asc')->get();
        $properties = Admin::orderBy('name', 'asc')->get();
        $contacts = Landlord::orderBy('name', 'asc')->get();
        $property_id = $propertyId;

        return view('admin.properties.calendar.event.add', compact('page', 'event_types', 'properties', 'contacts', 'property_id'));
    }

    public function store(Request $request, EventService $eventService, $propertyId) {
        $request->validate([
            'docs.*' => 'nullable|file|max:51200'
        ]);

        $requestData = $request->only(['event_type', 'external_user', 'external_user_name', 'cc', 'address_main_contact', 'date', 'date_to', 'time_from', 'time_to', 'description', 'recurrence', 'reminder', 'repeated_for']);
        $property = $request->property ? $request->property : array();
        $contacts = $request->contacts ? $request->contacts : array();
        $files = $request->file('docs', []);

        $requestData['repeated_for'] = $request->repeated_for ? $request->repeated_for : '4';
        
        $event = $eventService->createEvent($requestData, $property, $contacts, $files, $propertyId, $created_by_type = 'property');

        return redirect()
        ->route( 'admin.properties.calendar', ['savedState' => 'true', 'id' => $propertyId])
        ->withFlashMessage( 'Event created successfully!' )
        ->withFlashType( 'success' );
    }

    public function edit(Events $id, $propertyId, $created_by_type = 'property') {
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

        $event_property = \DB::table('event_property')->where('event_id', $event->id)->first();
        $event_contacts = \DB::table('event_contacts')->where('event_id', $event->id)->get();
        // $event_providers = \DB::table('event_providers')->where('event_id', $event->id)->get();
        $event_types = EventType::orderBy('event_name', 'asc')->get();
        $properties = Property::orderBy('created_at', 'asc')->get();
        $contacts = Contractor::orderBy('name', 'asc')->get();
        // $providers = Supplier::orderby('name', 'desc')->get();
        $property_id = $propertyId;

        return view('admin.properties.calendar.event.edit', compact('page', 'event_types', 'properties', 'event', 'event_property', 'event_contacts', 'contacts', 'property_id'));
    }

    public function update(Request $request, Events $id, $propertyId, $created_by_type = 'property') {
        $request->validate([
            'docs.*' => 'nullable|file|max:51200'
        ]);
        
        $eventService = new EventService();  // Initialize the service
        $requestData = $request->only([
            'event_type', 'date', 'time_from', 'time_to', 'external_user_name', 'cc', 'address_main_contact',
            'description', 'comment', 'property', 'recurrence', 'date_to', 'contacts', 'repeated_for', 'apply_to_future'
        ]);

        $requestData['property'] = !empty($requestData['property']) ? $requestData['property'] : array();
        $requestData['contacts'] = !empty($requestData['contacts']) ? $requestData['contacts'] : array();
        $requestData['files'] = $request->file('docs', []);
        $requestData['repeated_for'] = $request->repeated_for ? $request->repeated_for : '4';

        $event = $id;
    
        $eventService->updateEvent($event, $requestData, $propertyId, $created_by_type);  // Call the service method to update the event
    
        return redirect()
            ->route( 'admin.properties.calendar', ['savedState' => 'true', 'id' => $propertyId])
            ->withFlashMessage('Event updated successfully!')
            ->withFlashType('success');
    }

    public function fileDelete(EventDocs $id, $propertyId)
    {

        $eventDocs = $id;

        $filePath = public_path("events/event-{$eventDocs->event_id}/{$eventDocs->file_name}");

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        \DB::table('event_docs')->where('id', $eventDocs->id)->delete();

        return redirect()
        ->route( 'admin.properties.calendar', ['savedState' => 'true', 'id' => $propertyId])
        ->withFlashMessage('File deleted successfully!')
        ->withFlashType('success');
    }

    public function destroy(Events $id, $propertyId) {
        $event = $id;

        \DB::table('event_property')->where('event_id', $event->id)->delete();
        $event->delete();

        return redirect()
        ->route( 'admin.properties.calendar', ['savedState' => 'true', 'id' => $propertyId])
        ->withFlashMessage( 'Event deleted successfully!' )
        ->withFlashType( 'success' );
    }

    public function deleteAllRecurrences(Events $id, $propertyId) {
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
        ->route( 'admin.properties.calendar', ['savedState' => 'true', 'id' => $propertyId])
        ->withFlashMessage( 'All Recurrences deleted successfully!' )
        ->withFlashType( 'success' );
    }

}