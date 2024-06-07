<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user.index', only: ['index']),
            new Middleware('permission:user.create', only: ['create']),
            new Middleware('permission:user.edit', only: ['edit']),
            new Middleware('permission:user.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = User::query()->where('users.id', '!=', 1)->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return ucwords($row->name);
                })
                ->addColumn('role', function ($row) {
                    return $row->getRoleNames()->map(function ($role) {
                        $role = strtoupper($role);
                        if($role == 'ADMIN'){
                            return '<span class="badge badge-primary">'.$role.'</span>';
                        }
                        else{
                            return '<span class="badge badge-secondary">'.$role.'</span>';
                        }
                    })->implode(' ');
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->format('d-m-Y H:i:s') : '';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->format('d-m-Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    $action = "";
                    if(auth()->user()->can('user.edit')){
                        $action .= '<a href="'.route('admin.user.edit', $row->id).'" class="btn btn-sm btn-warning mx-2" title="Edit"><i class="fas fa-edit"></i></a>';
                    }
                    if(auth()->user()->can('user.delete')){
                        $action .= '<a href="javascript:;" class="btn btn-sm btn-danger" onclick="deletePrompt(\''.$row->id.'\')" title="Delete"><i class="fas fa-trash"></i></a>';
                    }

                    return $action;
                })
                ->rawColumns(['role', 'action'])
                ->make(true);
        }

        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('id', '!=', 1)->latest()->get();

        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::default(), 'confirmed'],
            'role' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $model = User::create([
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'password'  => Hash::make($request->input('password')),
                'status'    => 1,
                'user_created' => auth()->user()->id,
            ]);

            // Assign Role
            $model->assignRole(Role::findOrFail($request->input('role')));

            // Log Activity
            activity()->performedOn($model)->log('created');

            DB::commit();

            if ($model) {
                return response()->json([
                    "status" => true,
                    "message" => "Create user was successful.",
                ]);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "Create user was failed",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "status" => false,
                "message" => "Create user was failed " . $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = User::findOrFail($id);
        $roles = Role::where('id', '!=', 1)->latest()->get();

        return view('admin.user.edit', compact('model', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $model->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'user_updated' => auth()->user()->id,
            ]);

            // Assign Role
            $model->syncRoles(Role::findOrFail($request->input('role')));

            // Log Activity
            activity()->performedOn($model)->log('updated');

            DB::commit();

            if ($model) {
                return response()->json([
                    "status" => true,
                    "message" => "Update user was successful.",
                ]);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "Update user was failed.",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "status" => false,
                "message" => "Update user was failed " . $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $model = User::findOrFail($id);
            if ($model) {
                $model->delete();

                // Log Activity
                activity()->performedOn($model)->log('deleted');

                return response()->json([
                    "status" => true,
                    "message" => "Delete user was successful.",
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Delete user was failed.",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Delete user was failed " . $e->getMessage(),
            ]);
        }
    }
}
