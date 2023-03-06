<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'cash',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public static function getPlansForFamily()
    {
        $user = auth()->user();
        $familyUserIds = User::where('family_id', $user->family_id)->pluck('id')->toArray();

        $plans = Plan::whereIn('category_id', function ($query) use ($familyUserIds) {
            $query->select('categories.id')
                ->from('categories')
                ->join('groups', 'categories.group_id', '=', 'groups.id')
                ->whereIn('groups.user_id', $familyUserIds);
        })->get();

        return $plans;
    }


    static public function getLastPlansForAuthorizedUser()
    {
        $user = auth()->user();
        $familyUserIds = User::where('family_id', $user->family_id)->pluck('id')->toArray();

        $lastPlans = DB::table('plans')
            ->select('plans.*')
            ->whereIn('category_id', function ($query) use ($familyUserIds) {
                $query->select('categories.id')
                    ->from('categories')
                    ->join('groups', 'groups.id', '=', 'categories.group_id')
                    ->whereIn('user_id', $familyUserIds)
                    ->distinct();
            })
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('plans')
                    ->groupBy('category_id');
            })
            ->get();

        return $lastPlans;
    }


    static public function getSumLastPlansForAuthorizedUser()
    {
        $user = auth()->user();
        $familyUserIds = User::where('family_id', $user->family_id)->pluck('id')->toArray();

        $lastPlans = DB::table('plans')
            ->select('plans.*')
            ->whereIn('category_id', function ($query) use ($familyUserIds) {
                $query->select('categories.id')
                    ->from('categories')
                    ->join('groups', 'groups.id', '=', 'categories.group_id')
                    ->whereIn('user_id', $familyUserIds)
                    ->distinct();
            })
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('plans')
                    ->groupBy('category_id');
            })
            ->sum('cash');

        return $lastPlans;
    }


    static public function getPlansByCategory($categoryId)
    {

        return Plan::select('id','created_at','cash','cash_fact')
        ->where('category_id', $categoryId)
            ->orderByDesc('created_at')->get();
    }

}
