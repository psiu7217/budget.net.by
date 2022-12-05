<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;


class FamilyController extends Controller
{

    public function create()
    {
        dd('test');
    }

    public function find()
    {

    }

    public function show()
    {
        $user = new User;
        $user = $user->getAuthUser();
        dd($user->family);
//        dd(Uuid::uuid1());
//        dd($user->getAuthUser());

        return view('family.index', [
            'user' => $user,
        ]);
    }
}
