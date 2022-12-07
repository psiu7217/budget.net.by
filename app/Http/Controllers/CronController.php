<?php

namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\Http\Request;

class CronController extends Controller
{

    public function updatePlan()
    {
        $family = Family::find(1);
        $family->name = 'Anisko';
        $family->save();
    }
}
