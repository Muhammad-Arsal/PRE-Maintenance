<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $table = 'event';

    const RECURRENCE_RADIO = [
        'none'    => 'None',
        'daily'   => 'Daily',
        'weekly'  => 'Weekly',
        'monthly' => 'Monthly',
    ];
    
    protected $fillable = [
        'created_by_type','event_type', 'description', 'external_user', 'date_from', 'date_to', 'time_from', 'time_to', 'time_from_manual', 'time_to_manual', 'comment', 'recurrence', 'event_id', 'created_by', 'external_user_name', 'cc', 'address_main_contact'
    ];

    public function events()
    {
        return $this->hasMany(Events::class, 'event_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

    public function eventType() {
        return $this->belongsTo(EventType::class, 'event_type');
    }

    public function saveQuietlyWithoutEvents()
    {
        return static::withoutEvents(function () {
            return $this->save();
        });
    }

    public function EventProperty()
    {
        return $this->belongsToMany(Property::class, 'event_property', 'event_id', 'platform_user_id');
    }

    public function EventContacts()
    {
        return $this->belongsToMany(Contractor::class, 'event_contacts', 'event_id', 'contact_id');
    }

    public function permissions()
    {
        return $this->hasMany(EventPermissions::class, 'event_id');
    }

    public function docs() {
        return $this->hasMany(EventDocs::class, 'event_id');
    }
}
