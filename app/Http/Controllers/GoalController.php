<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $authUser = auth()->user();
        $familyUserIds = $authUser->getFamilyUserIds();

        $goals = Goal::whereHas('user', function ($query) use ($authUser, $familyUserIds) {
            $query->whereIn('id', [$authUser->id, ...$familyUserIds]);
        })
            ->orderByDesc('created_at')
            ->get();

        return view('goal.index', ['goals' => $goals]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('goal.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
//        dd($request);
        $validated = $request->validate([
            'title' => 'required|max:255|min:2',
            'deadline' => 'required|date',
            'target_amount' => 'required|numeric|min:1',
        ]);

        $goal = new Goal();
        $goal->fill($validated);
//        dd($goal);
        $goal->user_id = auth()->user()->id;
        $goal->is_achieved = false;
        $goal->save();

        return redirect()->route('goal.index')->with('status', 'Goal added successfully.');
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
     */
    public function edit($id)
    {
        $goal = Goal::findOrFail($id);

        if (!AccessController::checkPermission(Goal::class, $id)) {
            return redirect()->route('goal.index')->with('error', 'Access denied');
        }

        return view('goal.edit', ['goal' => $goal]);

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
        $goal = Goal::findOrFail($id);

        if (!AccessController::checkPermission(Goal::class, $id)) {
            return redirect()->route('goal.index')->with('error', 'Access denied');
        }

        $validated = $request->validate([
            'title' => 'required|max:255|min:2',
            'deadline' => 'required|date',
            'target_amount' => 'required|numeric|min:1',
        ]);

        $goal->update($validated);

        return redirect()->route('goal.index')->with('status', 'Goal Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $goal = Goal::findOrFail($id);
        if (!AccessController::checkPermission(Goal::class, $id)) {
            return redirect()->route('goal.index')->with('error', 'Access denied');
        }

        $goal->destroy($id);

        return redirect()->route('goal.index')->with('status', 'Goal deleted');
    }


    public function addAmount(Request $request) {

        $validated = $request->validate([
            'goal_id' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);

        $goal = Goal::findOrFail($validated['goal_id']);
        $goal->current_amount += $validated['amount'];

        if ($goal->current_amount >= $goal->target_amount) {
            $goal->is_achieved = true;
        }
        $goal->save();

        return redirect()->route('goal.index')->with('status', 'Goal add money');
    }
}
