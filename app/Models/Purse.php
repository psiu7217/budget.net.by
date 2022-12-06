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
        $purse->user_id = Auth::id();
        if (!$data['sort']) {
            $purse->sort = 1;
        }
        if ($data['hide'] == 'on') {
            $purse->hide = 1;
        }


//        dd($purse);

        $purse->save();

        return $purse;
    }
}
