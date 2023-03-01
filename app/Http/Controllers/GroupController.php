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
        return view('group.index', [
            'groups' => Group::getGroupsForAuthorizedUser(),
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
            'sort' => 'nullable|numeric',
        ]);

        $group = Group::create([
            'title' => $validated['title'],
            'sort' => $validated['sort'] ?? 1,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('group.index')->with('status', 'Group Added');
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
        if (!AccessController::checkPermission(Group::class, $id)) {
            return Redirect::route('group.index')->with('error', 'Access denied');
        }

        $group = Group::find($id);

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
        if (!AccessController::checkPermission(Group::class, $id)) {
            return Redirect::route('group.index')->with('error', 'Access denied');
        }

        $validated = $request->validate([
            'title' => 'required|max:255|min:2',
            'sort' => '',
        ]);

        $validated['sort'] = !empty($validated['sort']) ? $validated['sort'] : 1;

        $group = Group::find($id);

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
        $group = Group::with('categories')->find($id);

        if (!AccessController::checkPermission(Group::class, $id)) {
            return Redirect::route('group.index')->with('error', 'Access denied');
        }

        if ($group->categories->isNotEmpty()){
            return Redirect::route('group.index')->with('error', 'Please remove all categories from this group and try again');
        }

        $group->delete();

        return Redirect::route('group.index')->with('status', 'Group deleted');
    }

}
