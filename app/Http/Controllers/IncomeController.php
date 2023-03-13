<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Purse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('income.index', [
            'incomes' => Income::getIncomes(),
        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('income.create', [
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
            'purse_id' => 'required',
            'cash' => 'required',
        ]);

        $income = new Income($validated);
        $income->save();

        // Update Purse cash
        $purse = Purse::find($validated['purse_id']);
        $purse->increment('cash', $validated['cash']);

        return redirect()->route('income.index')->with('status', 'Income Added');
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     *
     */
    public function edit($id)
    {
        $income = Income::find($id);

        if (!AccessController::checkIncomeAccess($id)) {
            return redirect()->route('income.index')->with('error', 'Access denied');
        }


        return view('income.edit', [
            'purses' => Purse::accessibleByUser(),
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

        $user = new User;
        $user = $user->getAuthUser();
        $income = Income::find($id);

        if (!AccessController::checkIncomeAccess($id)) {
            return redirect()->route('income.index')->with('error', 'Access denied');
        }

        // Check if purse_id has changed
        if ($income->purse_id != $validated['purse_id']) {
            // Transfer funds between purses
            $old_purse = Purse::find($income->purse_id);
            $new_purse = Purse::find($validated['purse_id']);
            $old_purse->withdraw($income->cash);
            $new_purse->deposit($validated['cash']);
        } else {
            $cashDiff = $validated['cash'] - $income->cash;

            // Edit purses cash
            $purse = Purse::find($income->purse_id);
            $purse->cash += $cashDiff;
            $purse->save();
        }

        $income->fill($validated);
        $income->save();
        return Redirect::route('income.index')->with('status', 'Income updated');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $income = Income::findOrFail($id);

        if (!AccessController::checkIncomeAccess($id)) {
            return redirect()->route('income.index')->with('error', 'Access denied');
        }

        //Minus cash Purse
        $income->purse->update(['cash' => $income->purse->cash - $income->cash]);

        $income->delete();

        return redirect()->route('income.index')->with('status', 'Income Deleted');
    }

}
