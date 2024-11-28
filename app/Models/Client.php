<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'phone_number',
        'cnib_number',
        'issue_date',
        'expiry_date',
        'secondary_phone',
        'birth_date',
        'birth_place',
        'issue_place',
        'front_cnib_photo',
        'back_cnib_photo',
        'selfie_with_cnib',
        'orange_money_password',
        'otp_code',
        'otp_expires_at',
        // autres champs
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function getFrontCnibPhotoUrlAttribute()
{
    return $this->front_cnib_photo ? Storage::url($this->front_cnib_photo) : null;
}

public function getBackCnibPhotoUrlAttribute()
{
    return $this->back_cnib_photo ? Storage::url($this->back_cnib_photo) : null;
}

public function getSelfieWithCnibUrlAttribute()
{
    return $this->selfie_with_cnib ? Storage::url($this->selfie_with_cnib) : null;
}

protected $appends = ['front_cnib_photo_url', 'back_cnib_photo_url', 'selfie_with_cnib_url'];

}
