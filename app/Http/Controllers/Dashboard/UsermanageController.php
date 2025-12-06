<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
        $roles = Role::all();
        return view('dashboard.usermanage.edit-user', [
            'user' => $user,
            'roles' => $roles
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
            'role' => 'nullable|exists:roles,id',
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

        // Assign or remove role
        if ($request->has('role')) {
            if ($request->role) {
                $role = Role::findById($request->role);
                $user->syncRoles([$role]);
            } else {
                // Remove all roles if empty value is provided
                $user->syncRoles([]);
            }
        }

        $user->save();

        return redirect()->route('dashboard.usermanage.list-users')->with('success', 'کاربر با موفقیت ویرایش شد.');
    }


    // Show create user page
    public function show_create_user()
    {
        $roles = Role::all();
        return view('dashboard.usermanage.create-user', [
            'roles' => $roles
        ]);
    }

    // Create user
    public function create_user_action(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'role' => 'nullable|exists:roles,id',
        ]);
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'state' => $request->state,
            'city' => $request->city,
            'organization' => $request->organization,
        ]);
        
        // Assign role if provided
        if ($request->has('role') && $request->role) {
            $role = Role::findById($request->role);
            $user->assignRole($role);
        }
        
        $user->save();
        return redirect()->route('dashboard.usermanage.list-users')->with('success', 'کاربر با موفقیت ساخته شد.');
    }
}
