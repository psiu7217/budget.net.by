<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'purse_id',
        'cash',
        'created_at',
    ];

    public function purse()
    {
        return $this->belongsTo(Purse::class);
    }
}
