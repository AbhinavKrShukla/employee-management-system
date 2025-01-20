<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notices = Notice::latest()->get();
        return view('admin.notice.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.notice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date|after:today',
//            'name' => 'required|max:100'
        ]);

        $data = $request->all();
        $data['name'] = auth()->user()->name;
        Notice::create($data);
        return redirect()->back()->with('message', 'Notice Create Successfully');
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
        $notice = Notice::find($id);
        return view('admin.notice.edit', compact('notice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date|after:today',
//            'name' => 'required|max:100'
        ]);

        $data = $request->all();
        $data['name'] = auth()->user()->name;
        Notice::find($id)->update($data);
        return redirect()->route('notices.index')->with('message', 'Notice Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Notice::find($id)->delete();
        return redirect()->back()->with('message', 'Notice Deleted Successfully');
    }
}
