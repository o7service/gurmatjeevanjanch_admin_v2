<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class websiteLinks extends Model
{
    use HasFactory;
    protected $fillable = [
        'autoId',
        'title',
        'link',
        'isDeleted',
        'isBlocked',
        'addedById',
        'updatedById',
        'status',
    ];
    public function addedBy()
    {
        return $this->hasOne("App\Models\User", "id", "addedById");
    }
    public function updatedBy()
    {
        return $this->hasOne("App\Models\User", "id", "updatedById");
    }
}
