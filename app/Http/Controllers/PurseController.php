<?php

namespace App\Http\Controllers;

use App\Models\Purse;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class PurseController extends Controller
{


    public function __construct()
    {

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('purse.index', [
            'purses' => Purse::accessibleByUser(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('purse.create');
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

        Purse::create([
            'title' => $validated['title'],
            'sort' => $validated['sort'] ?? 1,
            'hide' => isset($validated['hide']),
            'description' => Crypt::encryptString($validated['description'] ?? ''),
            'number' => Crypt::encryptString($validated['number'] ?? ''),
            'pin' => Crypt::encryptString($validated['pin'] ?? ''),
            'user_id' => Auth::id(),
            'currency' => $validated['currency'] ?? '',
        ]);

        return redirect()->route('purse.create')->with('status', 'purse-added');
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
        if (!AccessController::checkPermission(Purse::class, $id)) {
            return redirect()->route('purse.index')->with('error', 'Access denied');
        }

        $purse = Purse::with(['checks' => function ($query) {
            $query->orderByDesc('created_at');
        }])->findOrFail($id);

        $decryptedFields = ['description', 'number', 'pin'];
        foreach ($decryptedFields as $field) {
            if ($purse->{$field}) {
                $purse->{$field} = Crypt::decryptString($purse->{$field});
            }
        }

        return view('purse.edit', [
            'purse' => $purse,
            'checks' => $purse->checks,
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

        if (!AccessController::checkPermission(Purse::class, $id)) {
            return redirect()->route('purse.index')->with('error', 'Access denied');
        }

        $validated = $request->validate([
            'title' => 'required|max:255|min:2',
            'sort' => '',
            'hide' => '',
            'description' => '',
            'number' => '',
            'pin' => '',
            'currency' => '',
            'cash' => '',
        ]);

        $purse = Purse::find($id);
        $purse->fill($validated);
        $purse->description = Crypt::encryptString($validated['description'] ?? '');
        $purse->number = Crypt::encryptString($validated['number'] ?? '');
        $purse->pin = Crypt::encryptString($validated['pin'] ?? '');
        $purse->sort = $validated['sort'] ?? 1;
        $purse->hide = isset($validated['hide']) && $validated['hide'] == 'on' ? 1 : 0;
        $purse->save();

        return Redirect::route('purse.index')->with('status', 'Purse ' . $purse->title . ' Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (!AccessController::checkPermission(Purse::class, $id)) {
            return redirect()->route('purse.index')->with('error', 'Access denied');
        }

        $purse = Purse::findOrFail($id);
        $purse->delete();

        return redirect()->route('purse.index')->with('status', 'Purse Deleted');

    }
}
