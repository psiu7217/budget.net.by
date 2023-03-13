<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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


    public static function getIncomes()
    {
        $user = Auth::user();

        return Income::whereHas('purse', function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('user.family', function($query) use ($user) {
                    $query->where('id', $user->family->id);
                })
                ->where('hide', 0);
        })
            ->orderByDesc('created_at')
            ->with('purse')
            ->get()
            ->map(function ($income) {
                $income->currency = $income->purse->currency;
                $income->purse_title = $income->purse->title;
                return $income;
            });
    }



    public static function getAllIncomesForAuthorizedUser()
    {
        $user = Auth::user();
        $incomes = Income::where('user_id', $user->id) // Incomes that belong to the authorized user
        ->orWhereHas('purse', function (Builder $query) use ($user) {
            $query->where('hide', 0) // Visible purses
            ->whereHas('user.family', function (Builder $query) use ($user) {
                $query->where('id', $user->family_id); // Purses that belong to the same family as the authorized user
            });
        })
            ->get();
        return $incomes;
    }

    static public function getSumIncomeCurrentMonth()
    {
        $date = User::getFamilyStartDate();
        return Income::where('created_at', '>', $date)
            ->orderByDesc('created_at')
            ->sum('cash');
    }
}
