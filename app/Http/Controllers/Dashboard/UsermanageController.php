<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsermanageController extends Controller
{
    // show the list of users
    public function list_users()
    {
        $users = User::all();
        return view('dashboard.usermanage.list-users', [
            'users' => $users
        ]);
    }

    // show the edit user page
    public function edit_user($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('dashboard.usermanage.list-users')->with('error', 'کاربر مورد نظر یافت نشد.');
        }
        return view('dashboard.usermanage.edit-user', [
            'user' => $user
        ]);
    }

    // update the user
    public function update_user(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
        ]);
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('dashboard.usermanage.list-users')->with('error', 'کاربر مورد نظر یافت نشد.');
        }

        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'state' => $request->state,
            'city' => $request->city,
            'organization' => $request->organization,
        ]);

        $user->save();

        return redirect()->route('dashboard.usermanage.list-users')->with('success', 'کاربر با موفقیت ویرایش شد.');
    }
}
