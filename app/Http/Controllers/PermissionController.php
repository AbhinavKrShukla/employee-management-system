<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use function Pest\Laravel\json;

class PermissionController extends Controller
{

    public $permissionList = [
        'department',
        'role',
        'permission',
        'user',
        'leave',
        'notice',
        'mail'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissionList = $this->permissionList;
        return view('admin.permission.create', compact('permissionList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'role_id' => 'required|unique:permissions,role_id',
        ]);

        Permission::create($request->all());
        return redirect()->back()->with('message','Permission created successfully');

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
        $permissionList = $this->permissionList;
        $permission = Permission::find($id);
        return view('admin.permission.edit', compact('permission', 'permissionList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);
        $permission = Permission::find($id);
        $permission->update($request->all());
        return redirect()->back()->with('message','Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Permission::find($id)->delete();
        return redirect()->back()->with('message','Permission deleted successfully');
    }
}
