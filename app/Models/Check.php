<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Check extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_id',
        'purse_id',
        'cash',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purse()
    {
        return $this->belongsTo(Purse::class);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return boolean
     */
    public function accessVerification()
    {
        if ($this->purse->user_id != Auth::id()) {
            return false;
        }

        return true;
    }
}
