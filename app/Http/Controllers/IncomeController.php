<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class IncomeController extends Controller
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



        return view('income.index', [
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

        return view('income.create', [
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
            'purse_id' => 'required',
            'cash' => 'required',
        ]);

        $income = new Income();
        $income->fill($validated);
        $income->save();

        return Redirect::route('income.index')->with('status', 'Income Added');
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
        $income = Income::find($id);
        $checkUser = false;

        foreach ($user->purses as $purse) {
            if ($purse->id == $income->purse_id) {
                $checkUser = true;
                break;
            }
        }

        if (!$checkUser) {
            return Redirect::route('income.index')->with('error', 'Access denied');
        }

        return view('income.edit', [
            'family'    => $user->family,
            'user'      => $user,
            'purses'    => $user->purses,
            'income'    => $income,
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
            'purse_id' => 'required',
            'cash' => 'required',
            'created_at' => '',
        ]);

        $income = Income::find($id);

        if ($income && $income->checkUserIncome($income)) {
            $income->fill($validated);
            $income->save();
            return Redirect::route('income.index')->with('status', 'Income updated');
        } else {
            return Redirect::route('income.index')->with('error', 'Access denied');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = Income::find($id);
        if ($income && $income->checkUserIncome($income)) {
            $income->delete();
            return Redirect::route('income.index')->with('status', 'Income Deleted');
        } else {
            return Redirect::route('income.index')->with('error', 'Access denied');
        }
    }

}
