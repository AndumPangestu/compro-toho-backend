<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\AdminUpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->routeIs('users.index')) {
            $role = 'user';
        } else if ($request->routeIs('admins.index')) {
            $role = 'admin';
        } else {
            $role = 'superadmin';
        }
        return view('users.index', compact('role'));
    }


    public function getUsers(Request $request)
    {
        if ($request->ajax()) {

            $data = User::query();

            if ($request->has('role') && !empty($request->role)) {
                $data->where('role', $request->role);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    $buttons = '<a href="' . route('users.show', $row->id) . '" class="btn btn-sm btn-info">View</a> ';

                    if (auth()->user()->role === 'superadmin') {
                        $buttons .=  '<a href="' . route('users.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> ' .
                            '<form action="' . route('users.destroy', $row->id) . '" method="POST" class="d-inline">'
                            . csrf_field()
                            . method_field("DELETE")
                            . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">Delete</button>'
                            . '</form>';
                    }

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }


        return abort(403);
    }

    public function create()
    {
        return view('users.user-form');
    }

    public function store(AdminUpdateUserRequest $request)
    {
        try {

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'password' => Hash::make($request->get('password')),
                'role' => $request->get('role'),
            ]);

            $routes = [
                'superadmin' => 'superadmins.index',
                'admin' => 'admins.index',
                'user' => 'users.index',
            ];

            return redirect()->route($routes[$user->role] ?? 'users.index')
                ->with('success', ucfirst($user->role) . ' created successfully');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateByAdmin(AdminUpdateUserRequest $request, User $user)
    {
        try {

            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'role' => $request->get('role'),
            ]);


            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->get('password')),
                ]);
            }

            $routes = [
                'superadmin' => 'superadmins.index',
                'admin' => 'admins.index',
                'user' => 'users.index',
            ];

            return redirect()->route($routes[$user->role] ?? 'users.index')
                ->with('success', ucfirst($user->role) . ' created successfully');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function updateByUser(UserUpdateRequest $request)
    {
        try {

            $user = auth()->user();

            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
                $user->sendEmailVerificationNotification();
            }

            return $this->sendSuccess(200, $user, "User updated successfully");
        } catch (\Exception $e) {

            return $this->sendError(500, null, "Internal server error");
        }
    }


    public function showByUser(Request $request)
    {

        $user = auth()->user();
        return $this->sendSuccess(200, new UserResource($user), "User fetched successfully");
    }


    public function show(Request $request, User $user)
    {

        $viewMode = true;
        return view('users.user-form', compact('user', 'viewMode'));
    }



    public function edit(User $user)
    {
        return view('users.user-form', compact('user'));
    }



    public function destroy(Request $request, User $user)
    {
        try {
            $user->delete();
            return $request->wantsJson()
                ? $this->sendSuccess(200, null, "User deleted successfully")
                : redirect()->route('users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {

            return $request->wantsJson()
                ? $this->sendError(500, null, "Internal server error")
                : redirect()->back()->with('error', $e->getMessage());
        }
    }
}
