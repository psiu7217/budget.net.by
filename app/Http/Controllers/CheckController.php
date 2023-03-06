<?php

namespace App\Http\Controllers;

use App\Models\Check;
use App\Models\Group;
use App\Models\Purse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $checks = Check::getAllChecksForUserAndFamily();
        return view('check.index', [
            'checks' => $checks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('check.create', [
            'groups' => Group::getGroupsForAuthorizedUser(),
            'purses' => Purse::accessibleByUser(),
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
            'category_id' => 'required',
            'purse_id' => 'required',
            'cash' => 'required',
        ]);


        $check = Check::create($validated);
        $check->purse->withdraw($check->cash);


        return Redirect::route('check.create')->with('status', 'Check Added');
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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $check = Check::findOrFail($id);

        if (!AccessController::checkPermission(Purse::class, $check->purse_id)) {
            return redirect()->route('purse.index')->with('error', 'Access denied');
        }

        return view('check.edit', [
            'groups' => Group::getGroupsForAuthorizedUser(),
            'purses' => Purse::accessibleByUser(),
            'check' => $check,
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
        $check = Check::findOrFail($id);

        if (!AccessController::checkPermission(Purse::class, $check->purse_id)) {
            return redirect()->route('purse.index')->with('error', 'Access denied');
        }

        $validated = $request->validate([
            'title' => 'required|max:255|min:2',
            'category_id' => 'required',
            'purse_id' => 'required',
            'cash' => 'required',
        ]);


        //Edit purses cash
        $check->purse->updateCash($check->purse_id, $check->cash - $validated['cash']);

        $check->update($validated);

        return Redirect::route('check.index')->with('status', 'Check Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $check = Check::findOrFail($id);

        if (!AccessController::checkPermission(Purse::class, $check->purse_id)) {
            return redirect()->route('purse.index')->with('error', 'Access denied');
        }

        $check->purse->deposit($check->cash);

        $check->delete();

        return Redirect::route('check.index')->with('status', 'Check deleted');
    }
}
