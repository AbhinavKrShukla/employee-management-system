@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5 mb-2">
        <form method="post" action="{{route('users.store')}}" enctype="multipart/form-data"> @csrf
            <div class="row justify-content-center">

                @if(Session::has('message'))
                    <div class="alert alert-success" style="width: 90%">
                        {{Session::get('message')}}
                    </div>
                @endif

                <div class="mb-3" style="width: 80%; font-size: 25px; font-weight: bold">
                    Create User
                </div>

                <div class="col-md-6">

                    <div class="card">
                        <div class="card-header fw-bold">{{ __('General Information') }}</div>

                        <div class="card-body">

                            <div class="form-group">
                                <label>First Name</label>
                                <input class="form-control @error('first-name') is-invalid @enderror" type="text"
                                       name="first-name"
                                       value="{{old('first-name')}}">
                                @error('first-name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Last Name</label>
                                <input class="form-control @error('last-name') is-invalid @enderror" type="text"
                                       name="last-name"
                                       value="{{old('last-name')}}">
                                @error('last-name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <input class="form-control @error('address') is-invalid @enderror" type="text"
                                       name="address"
                                       value="{{old('address')}}">
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="form-control @error('mobile_number') is-invalid @enderror" type="text"
                                       name="mobile_number"
                                       value="{{old('mobile_number')}}">
                                @error('mobile_number')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Department</label>
                                <div class="w-25">
                                    <select class="form-control" name="department_id">
                                        <option value="">Select</option>
                                        @foreach(\App\Models\Department::all() as $department)
                                            <option value="{{$department->id}}"
                                                {{$department->id == old('department_id') ? "selected" : ""}}>
                                                {{$department->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('department_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Designation</label>
                                <input class="form-control @error('designation') is-invalid @enderror" type="text"
                                       name="designation"
                                       value="{{old('designation')}}">
                                @error('designation')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Start Date</label>
                                <input class="form-control @error('start_from') is-invalid @enderror" type="date"
                                       name="start_from"
                                       value="{{old('start_from')}}">
                                @error('start_from')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Image</label>
                                <input class="form-control @error('image') is-invalid @enderror" type="file"
                                       name="image"
                                       value="{{old('image')}}" accept="image/*">
                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{--            ----------------------------------------------        --}}

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header fw-bold">{{ __('Login Information') }}</div>

                        <div class="card-body">

                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="text"
                                       name="email"
                                       value="{{old('email')}}">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password"
                                       name="password" value="{{old('password')}}">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                <div class="w-25">
                                    <select class="form-control" name="role_id">
                                        <option value="#">Select</option>
                                        @foreach(\App\Models\Role::all() as $role)
                                            <option value="{{$role->id}}"
                                                    @if($role->id == old('role_id')) selected @endif>
                                                {{$role->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('role_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>


                    </div>
                    <div class="form-group text-center mt-2">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>


            </div>
        </form>
    </div>
@endsection
