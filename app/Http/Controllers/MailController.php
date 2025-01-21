<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function create()
    {
        return view('admin.email.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'file'=>'mimes:pdf,docx,doc,jpeg,jpg,png',
            'body'=>'required'
        ]);

        $file = $request->file('file');

        $details = [
            'body' => $request->body,
            'file' => $request->file('file'),
        ];


        if($request->department)
        {
            $this->validate($request, [
                'department' => 'required',
            ]);
            $users = User::where('department_id', $request->department);
            foreach ($users as $user) {
                Mail::to($user->email)->send(new SendMail($details));
            }
        } elseif($request->person)
        {
            $this->validate($request, [
                'person' => 'required',
            ]);
            $user = User::find($request->person);
            $userEmail = $user->email;
//            $testEmail = 'quantum.reality.om@gmail.com';
            Mail::to($userEmail)->send(new SendMail($details));
        } else
        {
            $users = User::get();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new SendMail($details));
            }
        }

        return redirect()->back()->with('message', 'Mail Sent Successfully');

//        dd($request->all());

    }


}
