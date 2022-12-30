<?php

namespace App\Models;

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

    public function transactionsFrom()
    {
        return $this->hasMany(Transaction::class,'from_purse_id');
    }

    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class,'to_purse_id');
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
        $purse = Purse::find($id);
        $user = new User;
        $user = $user->getAuthUser();

        //Purse in family & Purse no hide
        if ((!in_array($purse->user_id, $user->userIds)) || ($purse->hide && $purse->user_id != $user->id)) {
            return false;
        }

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
}
