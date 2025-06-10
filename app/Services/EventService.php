<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Events;
use App\Models\Property;
use App\Models\EventType;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;

class EventService
{
    /**
     * Create a new EventService instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Initialization, if required.
    }

    /**
     * Your method to create events.
     *
     * @param array $requestData
     * @param array $platformUsers
     * @return \App\Models\Events
     */
    public function createEvent($requestData, $property, $contacts, $files, $tenantId, $created_by_type)
    {
        return \DB::transaction(function() use ($requestData, $property, $contacts, $files, $tenantId, $created_by_type) {
            // Parse dates and times and create the main event
            $event = $this->parseDatesAndTimesAndStoreEvent($requestData, $tenantId, $created_by_type);

            $this->handleFileUploads($event, $files);

            if(array_key_exists('reminder', $requestData)) {
                    if($requestData['reminder']) {

                    $template =  EmailTemplate::where('status','1')->where('type','Event Reminder')->first();
                    $event_type = EventType::find($event->event_type);

                    $e_date_from = Date('d/m/Y H:i', strtotime($event->date_from));
                    $e_date_to = Date('d/m/Y H:i', strtotime($event->date_to));

                    // foreach($property as $user) {
                    //     $platform_user = Property::find($user);
                    //     if($platform_user) {
                    //         if($platform_user->email) {
                    //             $data = array(
                    //                 'user_name' => $platform_user->name,
                    //                 'event_type' => $event_type->event_name,
                    //                 'date' => $e_date_from . ' -- ' . $e_date_to,
                    //                 'description' => $event->description,
                    //                 'template' => $template->content,
                    //                 'type' => 'internal_email',
                    //             );
    
                    //             \Mail::to($platform_user->email)->send(new \App\Mail\EventReminder($data));
                    //         }
                    //     }
                    // }

                    //Send Email to external user
                    if ($requestData['external_user']) {
                        
                        $external_user_data = array(
                            'user_name' => $requestData['external_user_name'] ? $requestData['external_user_name'] : $requestData['external_user'],   //for external user we don't have a user so we use its own email
                            'event_type' => $event_type->event_name,
                            'date' => $e_date_from . ' -- ' . $e_date_to,
                            'description' => $event->description,
                            'template' => $template->content,
                            'type' => 'external_email',
                        );

                      \Mail::to($requestData['external_user'])->send(new \App\Mail\EventReminder($external_user_data));
                    }
                }
            }

            // Associate platform users with the event
            $this->handlePlatformUsers($event, $property);

            //Associate contacts with the event
            $this->handleContacts($event, $contacts);

            //Associate providers with the event
            // $this->handleProviders($event, $requestData['providers']);

            // Handle recurrence creation
            $this->handleEventCreationRecurrence($event, $property, $contacts, $files, $requestData['repeated_for'], $tenantId, $created_by_type);
            
            return $event;
        });
    }

    protected function parseDatesAndTimesAndStoreEvent($requestData, $tenantId, $created_by_type)
    {
        if($created_by_type == 'tenant') {
            $created_by = $tenantId;
            $created_by_type = 'tenant';
        }elseif($created_by_type == 'property')
        {
            $created_by = $tenantId;
            $created_by_type = 'property';
        }
        else{
            $created_by = Auth::guard('admin')->user()->id;
            $created_by_type = 'admin';
        }
        $time_from = $requestData['time_from'];
        $time_to = $requestData['time_to'];
        
        $resultDateFrom = $this->getFormattedDateFrom($requestData['date'], $time_from);
        if($requestData['date_to']) {
            $resultDateTo = $this->getFormattedDateTo($requestData['date_to'], $time_to);
        } else {
            $resultDateTo = $this->getFormattedDateTo($requestData['date'], $time_to);
        }
    
        return Events::create([
            'event_type' => $requestData['event_type'],
            'external_user' => $requestData['external_user'],
            'external_user_name' => $requestData['external_user_name'],
            'cc' => $requestData['cc'],
            'address_main_contact' => $requestData['address_main_contact'],
            'date_from' => $resultDateFrom,
            'date_to' => $resultDateTo,
            'time_from' => $requestData['time_from'],
            'time_to' => $requestData['time_to'],
            'description' => $requestData['description'],
            'recurrence' => $requestData['recurrence'],
            'created_by' => $created_by,
            'created_by_type' => $created_by_type,
        ]);
    }


    protected function handleEventCreationRecurrence(Events $event, $property, $contacts, $files, $repeated_for, $tenantId, $created_by_type) {
        if(!$event->event()->exists())
        {
            $recurrences = [
                'daily'     => [
                    'times'     => $repeated_for ?? 4,
                    'function'  => 'addDay'
                ],
                'weekly'    => [
                    'times'     => $repeated_for ?? 4,
                    'function'  => 'addWeek'
                ],
                'monthly'    => [
                    'times'     => $repeated_for ?? 4,
                    'function'  => 'addMonth'
                ]
            ];
            $date_from = Carbon::parse($event->date_from);
            $date_to = Carbon::parse($event->date_to);
            $recurrence = $recurrences[$event->recurrence] ?? null;

            if($created_by_type == 'tenant') {
            $created_by = $tenantId;
            $created_by_type = 'tenant';
            }elseif($created_by_type == 'property')
            {
                $created_by = $tenantId;
                $created_by_type = 'property';
            }
            else{
                $created_by = Auth::guard('admin')->user()->id;
                $created_by_type = 'admin';
            }

            if($recurrence)
                for($i = 0; $i < $recurrence['times']; $i++)
                {
                    $date_from->{$recurrence['function']}();
                    $date_to->{$recurrence['function']}();
                    $saveStoredEvent = $event->events()->create([
                        'event_type' => $event->event_type,
                        'comment' => $event->comment,
                        'description'          => $event->description,
                        'date_from'    => $date_from,
                        'date_to'      => $date_to,
                        'time_from' => $event->time_from,
                        'time_to' => $event->time_to,
                        'recurrence'    => $event->recurrence,
                        'created_by' => $created_by,
                        'created_by_type' => $created_by_type,
                    ]);

                    $this->handleFileUploads($saveStoredEvent, $files);
                    $this->handlePlatformUsers($saveStoredEvent, $property);
                    $this->handleContacts($saveStoredEvent, $contacts);
                    // $this->handleProviders($saveStoredEvent, $providers);
                }
        }
    }

    public function updateEvent(Events $event, array $data, $tenantId, $created_by_type)
    {
        return \DB::transaction(function() use ($event, $data, $tenantId, $created_by_type) {

            $time_from = $data['time_from'];
            $time_to = $data['time_to'];
            
            $dateFrom = $this->getFormattedDateFrom($data['date'], $time_from);
            if($data['date_to']) {
                $dateTo = $this->getFormattedDateTo($data['date_to'], $time_to);
            } else {
                $dateTo = $this->getFormattedDateTo($data['date'], $time_to);
            }
        
            $originalDescription = $event->getOriginal('description');
            $originalRecurrence = $event->getOriginal('recurrence');
            $original_date_from = $event->getOriginal('date_from');
            $original_date_to = $event->getOriginal('date_to');


            $check = $event->update([
                'event_type' => $data['event_type'],
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'time_from' => $data['time_from'],
                'time_to' => $data['time_to'],
                'description' => $data['description'],
                'comment' => $data['comment'],
                'recurrence' => $data['recurrence'],
                'external_user_name' => $data['external_user_name'],
                'cc' => $data['cc'],
                'address_main_contact' => $data['address_main_contact'],
                'created_by' => $event->created_by,
                'created_by_type' => $event->created_by_type,
            ]);

            $this->handleFileUploads($event, $data['files']);

//             $queries = \DB::getQueryLog();
// \Log::info($queries);

            // dd($check);
            $this->handlePlatformUsers($event, $data['property']);

            $this->handleContacts($event, $data['contacts']);

            // $this->handleProviders($event, $data['providers']);

            if ($data['apply_to_future'] == 1) {
                $this->handleEventUpdateRecurrence($event, $data['property'], $data['contacts'], $originalDescription, $originalRecurrence, $data['files'], $data['repeated_for'], $data, $original_date_from, $original_date_to, $tenantId, $created_by_type);
            }

        });
    }

    public function handleEventUpdateRecurrence(Events $event, $platformUsers, $contacts, $originalDescription, $originalRecurrence, $files, $repeated_for, $data, $original_date_from, $original_date_to, $tenantId, $created_by_type)
    {
        if($event->events()->exists() || $event->event) 
        {
            $date_from_diff = Carbon::parse($original_date_from)->diffInSeconds($event->date_from, false);
            $date_to_diff = Carbon::parse($original_date_to)->diffInSeconds($event->date_to, false);

            if($event->event)
                $childEvents = $event->event->events()->whereDate('date_from', '>', $event->getOriginal('date_from'))->get();
            else
                $childEvents = $event->events;

            $time_from = $data['time_from'];
            $time_to = $data['time_to'];

            foreach($childEvents as $childEvent) 
            {
                if ($date_from_diff) {
                    $childEvent->date_from = Carbon::parse($childEvent->date_from)
                        ->addSeconds($date_from_diff)
                        ->setTimeFromTimeString($event->time_from);
                }
    
                if ($date_to_diff) {
                    $childEvent->date_to = Carbon::parse($childEvent->date_to)
                        ->addSeconds($date_to_diff)
                        ->setTimeFromTimeString($event->time_to);
                }
                if($time_from) 
                    $childEvent->time_from = $time_from;
                if($time_to)
                    $childEvent->time_to = $time_to;
                
                $childEvent->description = $event->description;

                $this->handleFileUploads($childEvent, $files);
                $this->handlePlatformUsers($childEvent, $platformUsers);
                $this->handleContacts($childEvent, $contacts);
                // $this->handleProviders($childEvent, $providers);
                $childEvent->save();
            }
        }

        if($originalRecurrence !== $event->recurrence && $event->recurrence != 'none') {
            $this->handleEventCreationRecurrence($event, $platformUsers, $contacts, $files, $repeated_for, $tenantId, $created_by_type);
        }
    }
    
    
    protected function getFormattedDateFrom($date, $timeFrom)
    {
        if (!empty($date) && !empty($timeFrom)) {
            $dateFrom = Carbon::createFromFormat('d/m/Y', $date);
            $timeFrom = Carbon::createFromFormat('H:i', $timeFrom);
            $dateFrom->setTime($timeFrom->hour, $timeFrom->minute, $timeFrom->second);
            return $dateFrom->format('Y-m-d H:i:s');
        } elseif (!empty($date)) {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d 00:00:00');
        } else {
            return null;
        }
    }
    
    protected function getFormattedDateTo($date, $timeTo)
    {
        if (!empty($date) && !empty($timeTo)) {
            $dateTo = Carbon::createFromFormat('d/m/Y', $date);
            $timeTo = Carbon::createFromFormat('H:i', $timeTo);
            $dateTo->setTime($timeTo->hour, $timeTo->minute, $timeTo->second);
            return $dateTo->format('Y-m-d H:i:s');
        } elseif (!empty($date)) {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d 00:00:00');
        } else {
            return null;
        }
    }
    

    protected function handlePlatformUsers($event, $property)
    {
        \DB::table('event_property')->where('event_id', $event->id)->delete();
        
        $platformUserRecords = [];
        foreach ($property as $user) {
            $platformUserRecords[] = [
                'event_id' => $event->id,
                'platform_user_id' => $user
            ];
        }
        
        \DB::table('event_property')->insert($platformUserRecords);
    }

    protected function handleContacts($event, $contacts)
    {
        \DB::table('event_contacts')->where('event_id', $event->id)->delete();
        
        $contactUserRecords = [];
        foreach ($contacts as $contact) {
            $contactUserRecords[] = [
                'event_id' => $event->id,
                'contact_id' => $contact
            ];
        }
        
        \DB::table('event_contacts')->insert($contactUserRecords);
    }

    protected function handleProviders($event, $providers)
    {
        \DB::table('event_providers')->where('event_id', $event->id)->delete();
        
        $providerRecords = [];
        foreach ($providers as $provider) {
            $providerRecords[] = [
                'event_id' => $event->id,
                'provider_id' => $provider
            ];
        }
        
        \DB::table('event_providers')->insert($providerRecords);
    }

    protected function handleFileUploads(Events $event, $files)
    {
        $directory = public_path("events/event-{$event->id}/");
    
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
    
        foreach ($files as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
    
            $file->move($directory, $fileName);
    
            \DB::table('event_docs')->insert([
                'event_id' => $event->id,
                'file_name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
