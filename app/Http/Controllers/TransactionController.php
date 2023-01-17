<?php

namespace App\Http\Controllers;

use App\Models\Purse;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TransactionController extends Controller
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


        return view('transaction.index', [
            'transactions' => $user->transactions->sortByDesc('created_at'),
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

        return view('transaction.create', [
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
            'from_purse_id' => 'required',
            'to_purse_id' => 'required',
            'rate' => 'required',
        ]);


        if ($validated['from_purse_id'] == $validated['to_purse_id']) {
            return Redirect::route('transaction.index')->with('error', 'Identical purses');
        }


        $transaction = new Transaction();
        $transaction->fill($validated);
        $transaction->save();


        //Edit purses cash
        $purse = new Purse();
        $purse->updateCash($transaction->from_purse_id, $transaction->rate, false);
        $purse->updateCash($transaction->to_purse_id, $transaction->rate, true);

        return Redirect::route('transaction.index')->with('status', 'Transaction Added');
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
