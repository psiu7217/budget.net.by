<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sort',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function checks()
    {
        return $this->hasManyThrough(Check::class, Category::class);
    }

    public function plans()
    {
        return $this->hasManyThrough(Plan::class, Category::class);
    }
}
