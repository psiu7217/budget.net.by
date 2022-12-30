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

    public function categories()
    {
        return $this->hasManyThrough(Category::class, Group::class);
    }

    public function incomes()
    {
        return $this->hasManyThrough(Income::class, Purse::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Purse::class, 'user_id', 'from_purse_id');
    }

    public function checks()
    {
        return $this->hasManyThrough(Check::class, Purse::class);
    }


    /**
     * Custom functions
     * @return mixed
     */
    public function getAuthUser()
    {
        $familyUser = User::find(Auth::id());
        $userIds = [];
        $userIds[] = $familyUser->id;

        //Create family user
        if ($familyUser->family && count($familyUser->family->users) > 1) {

            foreach ($familyUser->family->users as $user) {
                if ($user->id != $familyUser->id) {

                    $userIds[] = $user->id;

                    //Purses
                    if ($user->purses) {
                        foreach ($user->purses as $purse) {
                            if (!$purse->hide) {
                                $familyUser->purses->push($purse);
                            }
                        }
                    }

                    //Groups
                    if ($user->groups) {
                        foreach ($user->groups as $group) {
                            $familyUser->groups->push($group);
                        }
                    }

                    //Checks
                    if ($user->checks) {
                        foreach ($user->checks as $check) {
                            $familyUser->checks->push($check);
                        }
                    }
                }
            }
        }


        $sumTotal = 0;
        foreach ($familyUser->groups as $group) {
            $sum = 0;
            foreach ($group->categories as $category){
                if (isset($category->plans->sortBy('created_at')->last()->cash)) {
                    $sum += $category->plans->sortBy('created_at')->last()->cash;
                }
            }
            $group->sumPlans = $sum;
            $sumTotal += $sum;
        }
        $familyUser->sumTotalPlans = $sumTotal;

        $familyUser->userIds = $userIds;

        //sort
        $familyUser->groups = $familyUser->groups->sortByDesc('sort');

        return $familyUser;
    }

}

