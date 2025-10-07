<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    use HasFactory;
    protected $fillable = [
        'autoId',
        'categoryId',
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
    public function category()
    {
        return $this->hasOne("App\Models\Category", "id", "categoryId");
    }

    public function updatedBy()
    {
        return $this->hasOne("App\Models\User", "id", "updatedById");
    }
}
