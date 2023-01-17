<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;


class FamilyController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request  $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|min:2',
        ]);

        $family = new Family();
        $family->fill($validated);
        $family->newUniqueId();
        $family->save();

        $request->user()->family_id = $family->id;
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'family-updated');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function find(Request  $request)
    {
        $validated = $request->validate([
            'unique_code' => 'required|max:36|min:36',
        ]);

        $family = Family::where('unique_code', $validated['unique_code'])->first();

        if (!$family) {
            return Redirect::route('profile.edit')->withErrors(['unique_code' => 'Invalid Family code']);
        }

        $request->user()->family_id = $family->id;
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'family-updated');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request  $request)
    {
        $validated = $this->validateFamily($request);

        if (!$request->user()->family) {
            return Redirect::route('profile.edit')->withErrors(['name' => 'No family']);
        }

        $family = Family::find($request->user()->family->id);
        $family->fill($validated);
        $family->save();

//        $request->user()->family->name = $validated['name'];
//        $request->user()->family->first_day = $validated['first_day'];
//        $request->user()->family->save();

        return Redirect::route('profile.edit')->with('status', 'family-updated');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leaveFamily(Request  $request)
    {
        $request->user()->family_id = null;
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'family-updated');
    }

    public function show()
    {
        $user = new User;
        $user = $user->getAuthUser();
        dd($user->family);
//        dd(Uuid::uuid1());
//        dd($user->getAuthUser());

        return view('family.index', [
            'user' => $user,
        ]);
    }

    private function validateFamily(Request  $request)
    {
        return $request->validate([
            'name' => 'required|max:255|min:2',
            'first_day' => 'required|max:31|min:1|numeric',
        ]);
    }
}
