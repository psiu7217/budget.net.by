<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate',
        'from_purse_id',
        'to_purse_id',
    ];

    public function purse()
    {
        return $this->belongsTo(Purse::class,'from_purse_id','id');
    }

    public function purseTo()
    {
        return $this->belongsTo(Purse::class,'to_purse_id','id');
    }


    static public function getAllTransactionsForAuthUser()
    {
        $user = auth()->user();

        $transactions = Transaction::whereHas('purse', function($query) use ($user) {
            // Get transactions for authorized user or users in the same family
            $query->where('user_id', $user->id)
                ->orWhereHas('user', function($query) use ($user) {
                    $query->where('family_id', $user->family_id);
                });
        })->where(function($query) {
            // Get transactions where the purse is not hidden
            $query->whereHas('purse', function($query) {
                $query->where('hide', 0);
            })->orWhereHas('purseTo', function($query) {
                $query->where('hide', 0);
            });
        })
            ->orderByDesc('created_at')
            ->get();

        return $transactions;
    }




}
