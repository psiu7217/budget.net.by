<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Purse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{


    static public function checkPursePermission($purseId)
    {
        $user = Auth::user();

        $purse = Purse::find($purseId);

        // Check if the purse belongs to the user
        if ($purse->user_id == $user->id) {
            return true;
        }

        // Check if the purse belongs to a user from the same family and the hide = 0 property
        if ($user->family_id && $purse->user->family_id == $user->family_id && $purse->hide == 0) {
            return true;
        }

        return false;
    }


    static public function checkGroupPermission($groupId)
    {
        $user = Auth::user();

        $group = Group::find($groupId);

        // Check if the Group belongs to the user
        if ($group->user_id == $user->id) {
            return true;
        }

        // Check if the Group belongs to a user from the same family
        if ($user->family_id && $group->user->family_id == $user->family_id) {
            return true;
        }

        return false;
    }



    static public function checkPermission($model, $id)
    {
        $user = Auth::user();

        $item = $model::find($id);

        if (!$item) {
            return false;
        }

        // Check if the item belongs to the user
        if ($item->user_id == $user->id) {
            return true;
        }

        // Check if the item belongs to a user from the same family
        if ($user->family_id && $item->user->family_id == $user->family_id) {
            // Only check 'hide' property for Purse model
            if ($model === Purse::class && $item->hide == 0) {
                return true;
            } else if ($model !== Purse::class) {
                return true;
            }
        }

        return false;
    }

}
