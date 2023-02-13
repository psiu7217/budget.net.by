<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */

    public function index()
    {
        $user = new User();
        $user = $user->getAuthUser();

        return view('statistics.index', [
            'groups' => $user->groups,
            'user' => $user,
        ]);
    }
}
