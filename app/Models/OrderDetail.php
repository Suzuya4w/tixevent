<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $guarded = [];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}
