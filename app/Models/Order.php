<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // (Opsional) Alias dari methods details di atas, fungsi sama saja
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    protected $casts = [
        'status' => \App\Enums\OrderStatus::class,
    ];
}
