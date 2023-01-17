<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $user = new User;
        $user = $user->getAuthUser();

        return view('group.index', [
            'groups' => $user->groups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('group.create');
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
            'sort' => '',
        ]);

        if (!isset($validated['sort'])){
            $validated['sort'] = 1;
        }

        $validated['user_id'] = Auth::id();

        $group = new Group();
        $group->fill($validated);
        $group->save();

        return Redirect::route('group.index')->with('status', 'Group Added');
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $group = Group::find($id);

        $user = new User;
        $user = $user->getAuthUser();

        if (!in_array($group->user_id, $user->userIds)) {
            return Redirect::route('group.index')->with('error', 'Access denied');
        }

        return view('group.edit', [
            'group' => $group,
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
            'sort' => '',
        ]);
        if (!isset($validated['sort'])){
            $validated['sort'] = 1;
        }

        $group = Group::find($id);

        $user = new User;
        $user = $user->getAuthUser();

        if (!in_array($group->user_id, $user->userIds)) {
            return Redirect::route('group.index')->with('error', 'Access denied');
        }

        $group->fill($validated);
        $group->save();

        return Redirect::route('group.index')->with('status', 'Group update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $group = Group::find($id);
        $user = new User;
        $user = $user->getAuthUser();

        if (!in_array($group->user_id, $user->userIds)) {
            return Redirect::route('group.index')->with('error', 'Access denied');
        }

        if (count($group->categories)){
            return Redirect::route('group.index')->with('error', 'Please remove all categories from this group and try again');
        }

        $group->delete();

        return Redirect::route('group.index')->with('status', 'Group deleted');
    }
}
