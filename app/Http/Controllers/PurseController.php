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
     * @return \Illuminate\View\View
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
            'family' => $user->family,
            'user' => $user,
            'purses' => $user->purses,
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
        $selectPurse = null;

        foreach ($user->purses as $purse) {
            if ($purse->id == $id) {
                $selectPurse = $purse;
                $selectPurse->description = Crypt::decryptString($selectPurse->description);
                $selectPurse->number = Crypt::decryptString($selectPurse->number);
                $selectPurse->pin = Crypt::decryptString($selectPurse->pin);
                break;
            }
        }

        if ($selectPurse == null) {
            return Redirect::route('purse.index')->with('error', 'Access denied');
        }

        return view('purse.edit', [
            'family' => $user->family,
            'user' => $user,
            'purse' => $selectPurse,
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

        if ($user->id == $purse->user_id) {
            $purse->delete();
            return Redirect::route('purse.index')->with('status', 'Purse Delete');
        } else {
            return Redirect::route('purse.index')->with('error', 'Access denied');
        }

    }
}