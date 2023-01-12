<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = new User;
        $user = $user->getAuthUser();

        //Purses not hidden
        $purses = collect();
        foreach ($user->purses as $purse) {
            if (!$purse->hide) {
                $purses->push($purse);
            }
        }

//        dd($purses->sum('cash'));

        return view('dashboard', [
            'checks' => $user->checks->sortByDesc('created_at')->splice(0, 3),
            'purses' => $purses,
            'groups' => $user->groups,
            'user' => $user,
        ]);
    }
}
