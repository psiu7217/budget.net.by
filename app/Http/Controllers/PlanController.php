<?php

namespace App\Http\Controllers;

use App\Models\Check;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'cash' => 'required',
        ]);

        $plan = Plan::find($id);
        $user = new User;
        $user = $user->getAuthUser();
        if (!in_array($plan->category->group->user_id, $user->userIds)) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }

        $plan->fill($validated);
        $plan->save();

        return Redirect::route('category.edit', $plan->category->id)->with('status', 'Plan Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Update all plans for all categories
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function closeMonth()
    {
        $user = new User;
        $user = $user->getAuthUser();

        foreach ($user->groups as $group) {
            foreach ($group->categories as $category){
                $currentPlan = $category->plans->sortByDesc('created_at')->first();
                $plan = $currentPlan->replicate();

                $sumChecks = Check::where('created_at', '>', date('Y-m-d', strtotime($currentPlan->created_at)))
                    ->where('created_at', '<', Carbon::now())
                    ->where('category_id', '=', $plan->category_id)
                    ->get()->sum('cash');


                $currentPlan->cash_fact = $sumChecks;
                $currentPlan->save();

                $plan->created_at = Carbon::now();
                $plan->cash_fact = 0;
                $plan->save();
            }
        }

        return Redirect::route('profile.edit')->with('status', 'Month Closed');
    }

    /**
     * Cansel Update all plans for all categories
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelCloseMonth()
    {
        $user = new User;
        $user = $user->getAuthUser();

        foreach ($user->groups as $group) {
            foreach ($group->categories as $category){
                $category->plans->sortByDesc('created_at')->first()->delete();
            }
        }

        return Redirect::route('profile.edit')->with('status', 'Cansel Month Closed');
    }
}
