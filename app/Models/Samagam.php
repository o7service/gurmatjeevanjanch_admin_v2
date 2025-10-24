<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Samagam extends Model
{
    use HasFactory;

    protected $fillable = [
        'autoId',
        'organizerName',
        'address',
        'details',
        'mapLink',
        'phone',
        'email',
        'startDate',
        'endDate',
        'isBlocked',
        'isDeleted',
        'status',
    ];
}
