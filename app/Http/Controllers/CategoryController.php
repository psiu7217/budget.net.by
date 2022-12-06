<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = new User;
        $user = $user->getAuthUser();

        return view('category.index', [
            'groups' => $user->groups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = new User;
        $user = $user->getAuthUser();
        $category = new Category();

        return view('category.create', [
            'groups' => $user->groups,
            'statuses' => $category->categoryStatuses(),
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
        ]);

        if ($request->get('hide') && $request->get('hide') == 'on') {
            $validated['hide'] = 1;
        }else {
            $validated['hide'] = 0;
        }

        $category = new Category();
        $category->fill($validated);
        $category->save();

        return Redirect::route('category.index')->with('status', 'Category Added');
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
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = new User;
        $user = $user->getAuthUser();
        $category = Category::find($id);

        if (!$category) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }
        if (Group::find($category->group_id)->user_id != Auth::id()) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }

        return view('category.edit', [
            'groups' => $user->groups,
            'category' => $category,
            'statuses' => $category->categoryStatuses(),
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
            'sort' => 'required',
            'group_id' => 'required',
            'status' => 'required',
        ]);

        if ($request->get('hide') && $request->get('hide') == 'on') {
            $validated['hide'] = 1;
        }else {
            $validated['hide'] = 0;
        }

        $category = Category::find($id);

        if (Group::find($category->group_id)->user_id != Auth::id()) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }

        $category->fill($validated);
        $category->save();

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

        if (!$category) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }

        if (Group::find($category->group_id)->user_id != Auth::id()) {
            return Redirect::route('category.index')->with('error', 'Access denied');
        }

        $category->delete();

        return Redirect::route('category.index')->with('status', 'Category deleted');
    }
}
