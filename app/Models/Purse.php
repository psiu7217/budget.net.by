<?php

namespace App\Models;

use App\Http\Controllers\AccessController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class Purse extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sort',
        'user_id',
        'hide',
        'currency',
        'cash',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function checks()
    {
        return $this->hasMany(Check::class);
    }

    public function fromTransactions()
    {
        return $this->hasMany(Transaction::class,'from_purse_id');
    }

    public function toTransactions()
    {
        return $this->hasMany(Transaction::class,'to_purse_id');
    }


    public function scopeAccessibleByUser($query)
    {
        $user = auth()->user();
        // Get IDs of all users in the same family as the current user
        $familyUserIds = User::where('family_id', $user->family_id)->pluck('id')->toArray();

        // Retrieve all the purses that belong to the user or a user from the same family
        $query->where(function($query) use ($user, $familyUserIds) {
            $query->where('user_id', $user->id)
                ->orWhere(function($query) use ($familyUserIds) {
                    $query->whereIn('user_id', $familyUserIds)
                        ->where('hide', 0);
                });
        })
            ->orderBy('title')
            ->get();

        // Decrypt the encrypted fields
        $purses = $query->get();
        foreach ($purses as $purse) {
            $decryptedFields = ['description', 'number', 'pin'];
            foreach ($decryptedFields as $field) {
                if ($purse->{$field}) {
                    $purse->{$field} = Crypt::decryptString($purse->{$field});
                }
            }
        }

        return $purses;
    }



    public function addPurse($data)
    {
        $purse = new Purse();
        $purse->fill($data);
        $purse->description = Crypt::encryptString($data['description']);
        $purse->number = Crypt::encryptString($data['number']);
        $purse->pin = Crypt::encryptString($data['pin']);
        $purse->user_id = Auth::id();
        if (!$data['sort']) {
            $purse->sort = 1;
        }
        if (isset($data['hide']) && $data['hide'] == 'on') {
            $purse->hide = 1;
        }else {
            $purse->hide = 0;
        }

        $purse->save();

        return $purse;
    }

    public function updatePurse($data, $id)
    {
        //Purse in family & Purse no hide
        if (!AccessController::checkPermission(Purse::class, $id)) {
            return false;
        }

        $purse = Purse::find($id);
        $user = new User;
        $user = $user->getAuthUser();

        $purse->fill($data);
        $purse->description = Crypt::encryptString($data['description']);
        $purse->number = Crypt::encryptString($data['number']);
        $purse->pin = Crypt::encryptString($data['pin']);
        if (!$data['sort']) {
            $purse->sort = 1;
        }
        if (isset($data['hide']) && $data['hide'] == 'on') {
            $purse->hide = 1;
        }else {
            $purse->hide = 0;
        }

        $purse->save();

        return $purse;
    }

    public function updateCash($id, $cash, $plus = true)
    {
        $purse = Purse::find($id);
        if ($plus) {
            $purse->cash += $cash;
        }else {
            $purse->cash -= $cash;
        }

        $purse->save();
    }

    public function sumIncomes()
    {
        return $this->incomes->sum('cash');
    }

    public function sumChecks()
    {
        return $this->checks->sum('cash');
    }

    public function balance() {
        return $this->incomes->sum('cash') - $this->checks->sum('cash');
    }


    /**
     * Withdraw a certain amount from the purse
     *
     * @param float $amount
     * @return bool
     */
    public function withdraw(float $amount): bool
    {
        $purse = $this;

        if (!$purse) {
            return false;
        }

        $currentBalance = $purse->cash;

        if ($amount > $currentBalance) {
            return false;
        }

        $newBalance = $currentBalance - $amount;
        $purse->cash = $newBalance;

        return $purse->save();
    }

    /**
     * Deposit a certain amount to the purse
     *
     * @param float $amount
     * @return bool
     */
    public function deposit(float $amount): bool
    {
        $purse = $this;

        if (!$purse) {
            return false;
        }

        $currentBalance = $purse->cash;

        $newBalance = $currentBalance + $amount;
        $purse->cash = $newBalance;

        return $purse->save();
    }





}
