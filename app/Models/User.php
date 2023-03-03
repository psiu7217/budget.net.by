<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
        'first_day',
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

    public function getFamilyUserIdsAttribute()
    {
        if ($this->family) {
            return $this->family->users()->pluck('id')->toArray();
        }

        return [];
    }

    /**
     * Custom functions
     * @return mixed
     */
    static public function getAuthUser()
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

        //First day month
        if ($familyUser->family) {
            $familyUser->first_day = $familyUser->family->first_day;
        }

        if ($familyUser->first_day < 10) {
            $familyUser->first_day = '0'.$familyUser->first_day;
        }

        //Start date current month
        $familyUser->start_date_month = date('Y-m-' . $familyUser->first_day);
        if (strtotime(date('Y-m-j')) < strtotime($familyUser->start_date_month)) {
            $familyUser->start_date_month = date('Y-m-' . $familyUser->first_day, strtotime(' -1 month'));
        }


        //Sum plans & checks
        $sumTotalPlans = 0;
        $sumTotalChecks = 0;
        foreach ($familyUser->groups as $group) {
            $sumPlan = 0;
            $sumCheck = 0;
            foreach ($group->categories as $category){
                if (isset($category->plans->sortBy('created_at')->last()->cash)) {
                    $sumPlan += $category->plans->sortBy('created_at')->last()->cash;
                }
                $sumCheck += $category->checks->where('created_at', '>', $familyUser->start_date_month)->sum('cash');
            }
            $group->sumPlans = $sumPlan;
            $group->sumChecks = $sumCheck;
            $sumTotalPlans += $sumPlan;
            $sumTotalChecks += $sumCheck;
        }
        $familyUser->sumTotalPlans = $sumTotalPlans;
        $familyUser->sumTotalChecks = $sumTotalChecks;

        $familyUser->userIds = $userIds;




        //sort
        $familyUser->groups = $familyUser->groups->sortByDesc('sort');

        return $familyUser;
    }


    static public function getAuthUser_new()
    {

        $familyId = Auth::user()->family_id;

        // Get all users in the family and eager load their related data
        $users = User::with(['purses' => function ($query) {
            // Filter out hidden purses for each user
            $query->where('hide', 0)->orWhere('user_id', Auth::id());
        }, 'groups', 'checks'])
            ->where('family_id', $familyId)
            ->get();

        // Merge the related data for all users in the family
        $familyUser = new User();
        $familyUser->first_day = Auth::user()->first_day;
        $familyUser->purses = $users->pluck('purses')->collapse();
        $familyUser->groups = $users->pluck('groups')->collapse();
        $familyUser->checks = $users->pluck('checks')->collapse();

        foreach ($users as $user) {
            $userIds[] = $user->id;
        }


        // Get the first day of the month and format it with leading zeros
        $familyUser->first_day = str_pad(Auth::user()->family ? Auth::user()->family->first_day : 1, 2, '0', STR_PAD_LEFT);


        // Get the start date of the current month or the previous month if today is before the first day of the current month
        $startDateMonth = date('Y-m-' . $familyUser->first_day, strtotime('this month'));
        if (strtotime(date('Y-m-j')) < strtotime($startDateMonth)) {
            $startDateMonth = date('Y-m-' . $familyUser->first_day, strtotime('last month'));
        }
        $familyUser->start_date_month = $startDateMonth;

        return $familyUser;

        // Get the sum of plans and checks for each group and sort them by group sort in descending order
        $groups = $familyUser->groups->map(function ($group) use ($familyUser) {
            $sumPlan = $group->categories->max('plans.cash');
            $sumCheck = $group->categories->sum(function ($category) use ($familyUser) {
                return $category->checks->where('created_at', '>', $familyUser->start_date_month)->sum('cash');
            });

            return [
                'group' => $group,
                'sumPlan' => $sumPlan,
                'sumCheck' => $sumCheck,
            ];
        })->sortByDesc('group.sort');

        // Get the total sum of plans and checks for all groups
        $sumTotalPlans = $groups->sum('sumPlan');
        $sumTotalChecks = $groups->sum('sumCheck');

        // Set the user IDs, total sums, and groups
        $familyUser->userIds = $userIds;
        $familyUser->sumTotalPlans = $sumTotalPlans;
        $familyUser->sumTotalChecks = $sumTotalChecks;
        $familyUser->groups = $groups->pluck('group');

        // Return the family user
        return $familyUser;
    }



}

