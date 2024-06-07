<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:role.index', only: ['index']),
            new Middleware('permission:role.create', only: ['create', 'store']),
            new Middleware('permission:role.assign', only: ['assign', 'assignSave']),
            new Middleware('permission:role.edit', only: ['edit', 'update']),
            new Middleware('permission:role.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Role::query()->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return ucwords($row->name);
                })
                ->editColumn('guard_name', function ($row) {
                    return ucwords($row->guard_name);
                })
                ->addColumn('permissions', function ($data) {
                    return $data->getPermissionNames()->map(function ($permission) {
                        return '<span class="badge badge-primary mx-1 my-1">' . $permission . '</span>';
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
                    if(auth()->user()->can('role.assign')){
                        $action .= '<a href="'.route('admin.role.assign', $row->id).'" class="btn btn-sm btn-dark" title="Assign"><i class="fas fa-key"></i></a>';
                    }
                    if(auth()->user()->can('role.edit')){
                        $action .= '<a href="'.route('admin.role.edit', $row->id).'" class="btn btn-sm btn-warning mx-2" title="Edit"><i class="fas fa-edit"></i></a>';
                    }
                    if(auth()->user()->can('role.delete')){
                        $action .= '<a href="javascript:;" class="btn btn-sm btn-danger" onclick="deletePrompt(\''.$row->id.'\')" title="Delete"><i class="fas fa-trash"></i></a>';
                    }

                    return $action;
                })
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }

        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'guard_name' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $model = Role::create([
                'name'      => $request->input('name'),
                'guard_name' => $request->input('guard_name'),
            ]);

            // Log Activity
            activity()->performedOn($model)->log('created');

            DB::commit();

            if ($model) {
                return response()->json([
                    "status" => true,
                    "message" => "Create role was successful.",
                ]);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "Create role was failed",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "status" => false,
                "message" => "Create role was failed " . $e->getMessage(),
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
        $model = Role::findOrFail($id);

        return view('admin.role.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Role::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $id],
            'guard_name' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $model->update([
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard_name'),
            ]);

            // Log Activity
            activity()->performedOn($model)->log('updated');

            DB::commit();

            if ($model) {
                return response()->json([
                    "status" => true,
                    "message" => "Update role was successful.",
                ]);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "Update role was failed.",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "status" => false,
                "message" => "Update role was failed " . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for assign the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign($id)
    {
        $model = Role::findOrFail($id);
        $permissionOfRole = $model->permissions()->pluck('name')->toArray();
    	$permissions = Permission::orderBy('id', 'DESC')->get();

        return view('admin.role.assign', compact('model', 'permissions', 'permissionOfRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required',
            // 'guard_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first(),
            ]);
        }
        else {
            try {
                $permissions = array();
                if(isset($request->permissions))
    	        	$permissions = $request->permissions;

                $model = Role::findOrFail($request->id);
                if(is_array($permissions)){
                    // Sync Permission
                    app()[PermissionRegistrar::class]->forgetCachedPermissions();
                    $model->syncPermissions($permissions);
                    app()[PermissionRegistrar::class]->forgetCachedPermissions();
                }

                // Log Activity
                activity()->performedOn($model)->log('edited');

                if ($model) {
                    return response()->json([
                        "status" => true,
                        "message" => "Update assign permission was successful.",
                    ]);
                }
                else{
                    return response()->json([
                        "status" => false,
                        "message" => "Update assign permission was failed.",
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    "status" => false,
                    "message" => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $model = Role::findOrFail($id);
            if ($model) {
                if($model->name != "superadmin"){
                    foreach($model->permissions->pluck('name')->toArray() as $name){
                        $model->revokePermissionTo($name);
                    }
                    $model->delete();

                    // Log Activity
                    activity()->performedOn($model)->log('deleted');

                    return response()->json([
                        "status" => true,
                        "message" => "Delete role was successful.",
                    ]);
                }
                else {
                    return response()->json([
                        "status" => false,
                        "message" => "Delete role was failed.",
                    ]);
                }
            }
            else {
                return response()->json([
                    "status" => false,
                    "message" => "Role is not available.",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Delete role was failed " . $e->getMessage(),
            ]);
        }
    }
}
