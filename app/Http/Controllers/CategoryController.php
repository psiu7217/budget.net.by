<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Check;
use App\Models\Group;
use App\Models\Plan;
use App\Models\Purse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('category.index', [
            'sumPurse' => Purse::getTotalPurseCashForFamily(),
            'groups' => Category::getGroupsForAuthorizedUser(),
            'sumPlans' => Plan::getSumLastPlansForAuthorizedUser(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('category.create', [
            'groups' => Group::getGroupsForAuthorizedUser(),
            'statuses' => Category::categoryStatuses(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|min:2',
            'sort' => 'required',
            'group_id' => 'required',
            'status' => 'required',
            'hide' => 'sometimes|nullable|boolean',
        ]);

        $validated['hide'] = $request->has('hide');

        $category = Category::create($validated);

        Plan::create([
            'category_id' => $category->id,
            'cash' => 0,
        ]);

        return redirect()->route('category.index')->with('status', 'Category Added');
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
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $category = Category::find($id);

        if (!AccessController::checkPermission(Group::class, $category->group_id)) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }


        return view('category.edit', [
            'category' => $category,
            'checks' => Check::getAllChecksForCategory($id),
            'groups' => Group::getGroupsForAuthorizedUser(),
            'statuses' => Category::categoryStatuses(),
            'plans' => Plan::getPlansByCategory($id),
        ]);
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
        $category = Category::find($id);
        if (!AccessController::checkPermission(Group::class, $category->group_id)) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }

        $validated = $request->validate([
            'title' => 'required|max:255|min:2',
            'sort' => 'required',
            'group_id' => 'required',
            'status' => 'required',
        ]);

        $validated['hide'] = $request->filled('hide') ? 1 : 0;

        $category->update($validated);

        return Redirect::route('category.index')->with('status', 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!AccessController::checkPermission(Group::class, $category->group_id)) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }

        Plan::whereIn('category_id', [$category->id])->delete();
        Check::whereIn('category_id', [$category->id])->delete();

        $category->destroy($id);

        return Redirect::route('category.index')->with('status', 'Category deleted');
    }


    public function getSumChecksAttribute()
    {
        $user = User::getAuthUser();
        return $this->checks->where('created_at', '>', $user->start_date_month)->sum('cash');
    }

    public function getLatestPlanAmountAttribute()
    {
        return $this->plans()->latest('created_at')->value('cash');
    }


}
