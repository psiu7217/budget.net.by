<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Check;
use App\Models\Group;
use App\Models\Income;
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
        $sumIncomeCurrentMonth = Income::getSumIncomeCurrentMonth();

        return view('dashboard', [
            'checks' => Check::getAllChecksForUserAndFamily()->splice(0, 3),
            'purses' => Purse::getVisiblePursesOnlyTitleCash(),
            'groups' => Category::getGroupsForAuthorizedUser(),
            'sumTotalChecks' => $sumTotalChecks,
            'sumTotalPlans' => $sumTotalPlans,
            'sumIncomeCurrentMonth' => $sumIncomeCurrentMonth,
        ]);
    }
}
