<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'target_amount', 'current_amount', 'deadline', 'user_id', 'is_achieved'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentage()
    {
        return round(($this->current_amount / $this->target_amount) * 100, 2);
    }
}
