<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = Leave::latest()->get();
        return view('admin.leave.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaves = Leave::latest()->where('user_id', auth()->user()->id)->get();
        return view('admin.leave.create', compact('leaves'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'from' => 'required',
            'to' => 'required|after:from',
            'type' => 'required',
            'description' => 'required'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 0; // pending
        $data['message'] = '';
        Leave::create($data);

        return redirect()->back()->with('message', 'Leave Requested Successfully');
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
        if(Leave::find($id)->status != 0)
        {
            return redirect()->back()->with('message', 'Leave is already approved!');
        }

        $leave = Leave::find($id);
        return view('admin.leave.edit', compact('leave'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(Leave::find($id)->status != 0)
        {
            return redirect()->back()->with('message', 'Leave is already approved!');
        }

        $this->validate($request, [
            'from' => 'required|date|after:today',
            'to' => 'required|after:from',
            'type' => 'required',
            'description' => 'required'
        ]);

        $leave = Leave::find($id);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 0; // pending
        $data['message'] = '';
        $leave->update($data);

        return redirect()->route('leaves.create')->with('message', 'Leave Requested Successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Leave::find($id)->status != 0)
        {
            return redirect()->back()->with('message', 'Leave is already approved!');
        }

        Leave::find($id)->delete();
        return redirect()->back()->with('message', 'Leave Deleted!');
    }

    /**
     * Accepts or rejects a leave
     */
    public function acceptRejectLeave(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required',
            'message' => 'required'
        ]);

        $leave = Leave::find($id);
        if($leave->status != 0)
        {
            return redirect()->back()->with('message', 'Leave is already approved');
        }

        $status = $request->status;
        $message = $request->message;
        $leave->update([
            'status' => $status,
            'message' => $message
        ]);

        return redirect()->back()->with('message', 'Leave updated');

    }
}
