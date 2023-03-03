<?php

namespace App\Http\Controllers;

use App\Models\Purse;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return view('transaction.index', [
            'transactions' => Transaction::getAllTransactionsForAuthUser(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('transaction.create', [
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
            'from_purse_id' => 'required',
            'to_purse_id' => 'required',
            'rate' => 'required',
        ]);


        if ($validated['from_purse_id'] === $validated['to_purse_id']) {
            return redirect()->route('transaction.index')->with('error', 'Identical purses');
        }


        $transaction = new Transaction($validated);

        DB::transaction(function () use ($transaction) {
            $transaction->save();

            $fromPurse = Purse::findOrFail($transaction->from_purse_id);
            $fromPurse->updateCash($transaction->from_purse_id, $transaction->rate, false);

            $toPurse = Purse::findOrFail($transaction->to_purse_id);
            $toPurse->updateCash($transaction->to_purse_id, $transaction->rate, true);
        });

        return redirect()->route('transaction.create')->with('status', 'Transaction Added');
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
