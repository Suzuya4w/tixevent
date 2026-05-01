<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($voucher) {
            if (empty($voucher->slug)) {
                $voucher->slug = \Illuminate\Support\Str::slug($voucher->kode_voucher);
            }
        });
        
        static::updating(function ($voucher) {
            if (empty($voucher->slug) || $voucher->isDirty('kode_voucher')) {
                $voucher->slug = \Illuminate\Support\Str::slug($voucher->kode_voucher);
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'voucher_id');
    }
}
