<?php

namespace App\Http\Controllers;

use App\Models\Purse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class PurseController extends Controller
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

        foreach ($user->purses as $purse) {
            $purse->description = Crypt::decryptString($purse->description);
            $purse->number = Crypt::decryptString($purse->number);
            $purse->pin = Crypt::decryptString($purse->pin);
        }


        return view('purse.index', [
            'purses' => $user->purses,
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

        return view('purse.create', [
            'family' => $user->family,
            'user' => $user,
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
            'sort' => '',
            'hide' => '',
            'description' => '',
            'number' => '',
            'pin' => '',
            'currency' => '',
        ]);

        $purse = new Purse();
        $purse->addPurse($validated);


        return Redirect::route('purse.create')->with('status', 'purse-added');
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

        $purse = Purse::find($id);

        if ((!in_array($purse->user_id, $user->userIds)) || ($purse->hide && $purse->user_id != $user->id)) {
            return Redirect::route('purse.index')->with('error', 'Access denied');
        }

        $purse->description = Crypt::decryptString($purse->description);
        $purse->number = Crypt::decryptString($purse->number);
        $purse->pin = Crypt::decryptString($purse->pin);

        return view('purse.edit', [
            'purse' => $purse,
            'checks' => $purse->checks->sortByDesc('created_at'),
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
            'hide' => '',
            'description' => '',
            'number' => '',
            'pin' => '',
            'currency' => '',
        ]);

        $purse = new Purse();
        $purse = $purse->updatePurse($validated, $id);

        if ($purse) {
            return Redirect::route('purse.index')->with('status', 'Purse ' . $purse->title . ' Updated');
        } else {
            return Redirect::route('purse.index')->with('error', 'Access denied');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = new User;
        $user = $user->getAuthUser();

        $purse = Purse::find($id);

        if ((!in_array($purse->user_id, $user->userIds)) || ($purse->hide && $purse->user_id != $user->id)) {
            return Redirect::route('purse.index')->with('error', 'Access denied');
        }

        $purse->delete();
        return Redirect::route('purse.index')->with('status', 'Purse Delete');

    }
}
