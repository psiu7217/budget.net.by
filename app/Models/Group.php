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


    public static function getGroupsForAuthorizedUser()
    {
        $user = auth()->user();
        $family = $user->family;

        if ($family) {
            // User belongs to a family, get groups for the family
            $groupIds = [];
            foreach ($family->users as $familyUser) {
                $groupIds = array_merge($groupIds, $familyUser->groups()->pluck('id')->toArray());
            }
            $groups = self::whereIn('id', array_unique($groupIds))->get();
        } else {
            // User does not belong to a family, get user's groups
            $groups = $user->groups;
        }

        return $groups;
    }

}
