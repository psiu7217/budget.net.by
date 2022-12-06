<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Purse extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sort',
        'user_id',
        'hide',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
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
        if ($purse->user_id != Auth::id()) {
            return null;
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

    public function sumIncomes()
    {
        $sum = 0;
        foreach ($this->incomes as $income) {
            $sum += $income->cash;
        }

        return $sum;
    }
}
