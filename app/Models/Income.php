<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


    /**
     * Checking the user for access to Income
     *
     * @param  Income  $income
     * @return boolean
     */
    public function checkUserIncome($income)
    {
        $user = new User;
        $user = $user->getAuthUser();
        $checkUser = false;

        foreach ($user->purses as $purse) {
            if ($purse->id == $income->purse_id) {
                $checkUser = true;
                break;
            }
        }

        return $checkUser;

    }
}
