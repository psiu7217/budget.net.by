<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function group()
    {
        return $this->hasOneThrough(Group::class, Category::class);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return boolean
     */
    public function accessVerification()
    {
        $user = new User;
        $user = $user->getAuthUser();

        if (!in_array($this->purse->user_id, $user->userIds)) {
            return false;
        }
        return true;
    }


    static public function getAllChecksForCategory($category_id)
    {
        $date = User::getFamilyStartDate();
        return Check::where('category_id', $category_id)
            ->Where('created_at', '>', $date)
            ->orderByDesc('created_at')
            ->get();

    }


    static public function getAllChecksForUserAndFamily()
    {
        $authUser = auth()->user();

        $familyUserIds = $authUser->getFamilyUserIds();

        $checks = Check::whereHas('category.group.user', function ($query) use ($authUser, $familyUserIds) {
            $query->whereIn('users.id', [$authUser->id, ...$familyUserIds]);
        })
            ->with('purse')
            ->with('category.group')
            ->with('category')
            ->orderByDesc('created_at')
            ->get();

        return $checks;
    }

}
