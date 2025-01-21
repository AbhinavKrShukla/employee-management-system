@extends('admin.layouts.master')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{Session::get('message')}}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">{{ __('Send Mail') }}</div>

                    <div class="card-body">

                        <form action="{{route('mails.store')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label>Select</label>
                                <select id="email" class="form-control mt-2">
                                    <option value="0">Mail to all staffs</option>
                                    <option value="1">Choose Department</option>
                                    <option value="2">Choose Person</option>
                                </select>
                                @error('department')
                                <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                                @enderror
                                @error('person')
                                <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                                @enderror

                                <select id="department" class="form-control mt-2 @error('department') is-invalid @enderror" name="department">
                                        <option value="">Select Department</option>
                                    @foreach(\App\Models\Department::all() as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                                @error('department')
                                    <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                                @enderror

                                <select id="person" class="form-control mt-2 @error('person') is-invalid @enderror" name="person">
                                    <option value="">Select Person</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                                @error('person')
                                <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                                @enderror

                                <div class="form-group mt-2">
                                    <label>Body</label>
                                    <textarea class="form-control @error('body') is-invalid @enderror" name="body">

                                    </textarea>
                                    @error('body')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mt-2">
                                    <label>File</label>
                                    <input class="form-control @error('body') is-invalid @enderror" type="file" name="file">
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group text-center mt-2">
                                    <button class="btn btn-primary" type="submit">
                                        Send Mail
                                    </button>
                                </div>




                            </div>


                        </form>



                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    Some css style to hide the department and person div--}}
    <style>
        #department{
            display: none;
        }
        #person{
            display: none;
        }
    </style>

@endsection
