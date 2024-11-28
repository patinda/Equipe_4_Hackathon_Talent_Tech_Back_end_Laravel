<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'transaction_date',
        'transaction_type_id',
        'transaction_amount',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function typeTransaction()
    {
        return $this->belongsTo(TypeTransaction::class, 'transaction_type_id');
    }
}
