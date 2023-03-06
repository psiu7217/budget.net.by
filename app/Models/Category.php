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

    static public function categoryStatuses()
    {
        return [
            1 => 'Normal',
            2 => 'Deleted',
        ];
    }


    static public function getCategoriesForAuthorizedUser()
    {
        $user = auth()->user();
        $userId = $user->id;
        // Get IDs of all users in the same family as the current user
        $familyUserIds = User::where('family_id', $user->family_id)->pluck('id')->toArray();
        $familyId = $user->family_id;

        // get the categories for the authorized user and family members
        $categories = Category::select('categories.*', 'groups.title as gr_title')
            ->join('groups', 'groups.id', '=', 'categories.group_id')
            ->join('users', 'users.id', '=', 'groups.user_id')
            ->where(function ($query) use ($userId, $familyId) {
                $query->where('users.id', $userId) // authorized user
                ->orWhere('users.family_id', $familyId); // family members
            })
            ->distinct()
            ->get();

        return $categories;

    }

    static public function getGroupsForAuthorizedUser()
    {
        $user = auth()->user();
        $userId = $user->id;
        // Get IDs of all users in the same family as the current user
        $familyUserIds = User::where('family_id', $user->family_id)->pluck('id')->toArray();
        $familyId = $user->family_id;

        // get the categories for the authorized user and family members
        $categories = Category::with(['group.user', 'plans'])
            ->whereHas('group.user', function ($query) use ($userId, $familyId) {
                $query->where('users.id', $userId) // authorized user
                ->orWhere('users.family_id', $familyId); // family members
            })
            ->orderBy('group_id')
            ->get();


        // restructure the data to fit the expected output
        $result = [];
        foreach ($categories as $category) {
            $groupId = $category->group->id;
            $groupTitle = $category->group->title;
            $categoryId = $category->id;
            $categoryTitle = $category->title;
            $planCash = $category->plans->last() ? $category->plans->last()->cash : null;

            if (!array_key_exists($groupId, $result)) {
                $result[$groupId] = [];
                $result[$groupId]['groupCash'] = 0;
                $result[$groupId]['groupTitle'] = $groupTitle;
                $result[$groupId]['items'] = [];
            }

            $result[$groupId]['items'][] = [
                'categoryTitle' => $categoryTitle,
                'categoryId' => $categoryId,
                'planCash' => $planCash,
            ];

            $result[$groupId]['groupCash'] += $planCash;
        }

//        dd($result);
        return $result;
    }


}
