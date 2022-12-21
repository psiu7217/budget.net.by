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

        

        return view('dashboard', [
            'checks' => $user->checks->sortByDesc('created_at'),
        ]);
    }
}
