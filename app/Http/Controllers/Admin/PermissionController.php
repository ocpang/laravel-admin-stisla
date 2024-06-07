<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:permission.index', only: ['index']),
            new Middleware('permission:permission.create', only: ['create', 'store']),
            new Middleware('permission:permission.edit', only: ['edit', 'update']),
            new Middleware('permission:permission.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Permission::query()->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->format('d-m-Y H:i:s') : '';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->format('d-m-Y H:i:s') : '';
                })
                ->addColumn('action', function ($row) {
                    $action = "";
                    if(auth()->user()->can('permission.edit')){
                        $action .= '<a href="'.route('admin.permission.edit', $row->id).'" class="btn btn-sm btn-warning mx-2" title="Edit"><i class="fas fa-edit"></i></a>';
                    }
                    if(auth()->user()->can('permission.delete')){
                        $action .= '<a href="javascript:;" class="btn btn-sm btn-danger" onclick="deletePrompt(\''.$row->id.'\')" title="Delete"><i class="fas fa-trash"></i></a>';
                    }

                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions'],
            'guard_name' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $model = Permission::create([
                'name'      => $request->input('name'),
                'guard_name' => $request->input('guard_name'),
            ]);

            // Log Activity
            activity()->performedOn($model)->log('created');

            DB::commit();

            if ($model) {
                return response()->json([
                    "status" => true,
                    "message" => "Create permission was successful.",
                ]);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "Create permission was failed",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "status" => false,
                "message" => "Create permission was failed " . $e->getMessage(),
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
        $model = Permission::findOrFail($id);

        return view('admin.permission.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Permission::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $id],
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
                    "message" => "Update permission was successful.",
                ]);
            }
            else{
                return response()->json([
                    "status" => false,
                    "message" => "Update permission was failed.",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "status" => false,
                "message" => "Update permission was failed " . $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $model = Permission::findOrFail($id);
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
                        "message" => "Delete permission was successful.",
                    ]);
                }
                else {
                    return response()->json([
                        "status" => false,
                        "message" => "Delete permission was failed.",
                    ]);
                }
            }
            else {
                return response()->json([
                    "status" => false,
                    "message" => "Permission is not available.",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Delete permission was failed " . $e->getMessage(),
            ]);
        }
    }
}
