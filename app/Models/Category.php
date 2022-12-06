<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;



    protected $fillable = [
        'title',
        'status',
        'sort',
        'hide',
        'group_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function checks()
    {
        return $this->hasMany(Check::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function categoryStatuses()
    {
        return [
            1 => 'Normal',
            2 => 'Deleted',
        ];
    }
}
