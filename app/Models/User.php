<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the family that owns the user.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function purses()
    {
        return $this->hasMany(Purse::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function getAuthUser()
    {
        $user = User::find(Auth::id());

        $sumTotal = 0;
        foreach ($user->groups as $group) {
            $sum = 0;
            foreach ($group->categories as $category){
                $sum += $category->plans->sortBy('created_at')->last()->cash;
            }
            $group->sumPlans = $sum;
            $sumTotal += $sum;
        }
        $user->sumTotalPlans = $sumTotal;

        return $user;
    }
}
