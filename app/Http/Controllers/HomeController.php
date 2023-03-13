<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Check;
use App\Models\Group;
use App\Models\Plan;
use App\Models\Purse;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {

        $sumTotalChecks = Check::getSumTotalChecks();
        $sumTotalPlans = Plan::getSumLastPlansForAuthorizedUser();


        return view('dashboard', [
            'checks' => Check::getAllChecksForUserAndFamily()->splice(0, 3),
            'purses' => Purse::AccessibleByUser(),
            'groups' => Category::getGroupsForAuthorizedUser(),
//            'categories' => Category::getCategoriesForAuthorizedUser(),
            'sumTotalChecks' => $sumTotalChecks,
            'sumTotalPlans' => $sumTotalPlans,
        ]);
    }
}
