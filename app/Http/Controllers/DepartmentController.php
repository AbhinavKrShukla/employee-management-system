<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        return view('admin.department.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:departments',
        ]);
        $department = new Department();
        $department->name = $request->name;
        $department->description = $request->description;
        $department->save();
        return redirect()->back()->with('message','Department added successfully');
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
        $department = Department::find($id);
        return view('admin.department.edit', compact('department')) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'name'=>'required|unique:departments,name,'.$id,
        ]);

        $department = Department::find($id);
        $department->name = $request->input('name');
        $department->description = $request->input('description');
        $department->save();
        return redirect(route('departments.index'))->with('message','Department updated successfully');

//        Another way of doing it
//        $department = Department::find($id);
//        $data = $request->all();
//        $department->update($data);
//        return redirect(route('departments.index'))->with('message','Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::find($id);
        $department->delete();
        return redirect(route('departments.index'))->with('message','Department deleted successfully');
    }
}
