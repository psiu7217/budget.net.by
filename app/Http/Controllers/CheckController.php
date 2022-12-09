<?php

namespace App\Http\Controllers;

use App\Models\Check;
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
        $user = new User;
        $user = $user->getAuthUser();

        return view('check.index', [
            'groups' => $user->groups,
            'checks' => $user->checks->sortByDesc('created_at'),
            'user' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $user = new User;
        $user = $user->getAuthUser();

        return view('check.create', [
            'groups' => $user->groups,
            'purses' => $user->purses,
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


        $check = new Check();
        $check->fill($validated);
        $check->save();

        return Redirect::route('check.index')->with('status', 'Check Added');
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
        $user = new User;
        $user = $user->getAuthUser();
        $check = Check::find($id);

        if (!$check) {
            return Redirect::route('check.index')->with('error', 'Access denied');
        }
        if (!$check->accessVerification()) {
            return Redirect::route('check.index')->with('error', 'Access denied');
        }

        return view('check.edit', [
            'groups' => $user->groups,
            'purses' => $user->purses,
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
        $validated = $request->validate([
            'title' => 'required|max:255|min:2',
            'category_id' => 'required',
            'purse_id' => 'required',
            'cash' => 'required',
        ]);


        $check = Check::find($id);

        if (!$check) {
            return Redirect::route('check.index')->with('error', 'Access denied');
        }
        if (!$check->accessVerification()) {
            return Redirect::route('check.index')->with('error', 'Access denied');
        }

        $check->fill($validated);
        $check->save();

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
        $check = Check::find($id);

        if (!$check) {
            return Redirect::route('check.index')->with('error', 'Access denied');
        }
        if (!$check->accessVerification()) {
            return Redirect::route('check.index')->with('error', 'Access denied');
        }

        $check->delete();

        return Redirect::route('check.index')->with('status', 'Check deleted');
    }
}
