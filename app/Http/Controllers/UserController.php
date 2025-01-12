<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'address' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:12|min:5|unique:users',
            'department_id' => 'required|string|max:255',
            'role_id' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'start_from' => 'required|date',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        if($request->hasFile('image')){
            $image = $request->image->hashName();
            $request->image->move(public_path('profile'), $image);
        } else {
            $image = 'default.png';
        }
        $data['name'] = $request['first-name'].' '.$request['last-name'];
        $data['image'] = $image;
        $data['password'] = bcrypt($request['password']);

        $user = User::create($data);

        return redirect()->back()->with('message', 'User Added Successfully ');
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
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'address' => 'required|string|max:255',
            'mobile_number' => 'required|max:12|min:5|unique:users,mobile_number,'.$id,
            'department_id' => 'required|string|max:255',
            'role_id' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'start_from' => 'required|date',
        ]);

        if($request->password){
            $this->validate($request,[
                'password' => 'string|min:6',
            ]);
            $password = bcrypt($request['password']);
        } else {
            $password = $user->password;
        }

        if ($request->hasFile('image')) {
            $this->validate($request,[
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Unlink the previous image, only if it's not default image
            if($user->image != 'default.png'){
                unlink(public_path('profile').$user->image);
                return $user->image;
            }

            // Store the new image
            $image = $request->image->hashName();
            $request->image->move(public_path('profile'), $image);
        } else {
            $image = $user->image;
        }

        $data = $request->all();
        $data['name'] = $request['name'];
        $data['image'] = $image;
        $data['password'] = $password;

        $user->update($data);

        return redirect()->back()->with('message', 'User Updated Successfully ');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        // Check if user has default image, don't delete it from public path

        if($user->image != 'default.png'){
            unlink(public_path('profile/').$user->image);
        }
        $user->delete();
        return redirect()->back()->with('message', 'User Deleted Successfully ');
    }
}
