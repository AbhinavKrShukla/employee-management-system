@extends('admin.layouts.master')

@section('content')

<div class="container mt-3">
    <div class="row justify-content-center">

        @if(Session::has('message'))
            <div class="alert alert-success" style="width: 90%">
                {{Session::get('message')}}
            </div>
        @endif

            <div class="col-md-8">
        <form method="post" action="{{route('permissions.store')}}"> @csrf
                <div class="card">
                    <div class="card-header">{{ __('Permissions') }}</div>

                    <div class="card-body">

                        {{--    Roles Dropdown  --}}

                        <select class="form-select @error('role_id') is-invalid @enderror" name="role_id">
                            @foreach(\App\Models\Role::all() as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                            @error('role_id')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        {{--    Table for the permission    --}}

                        <table class="table table-dark table-striped mt-4">

                            <thead>
                                <tr>
                                    <th scope="col">Permission</th>
                                    <th scope="col">can-add</th>
                                    <th scope="col">can-edit</th>
                                    <th scope="col">can-view</th>
                                    <th scope="col">can-delete</th>
                                    <th scope="col">can-list</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($permissionList as $permissionItem)

                                <tr>
                                    <td>{{$permissionItem}}</td>
                                    <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-add]" value="1"></td>
                                    <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-edit]" value="1"></td>
                                    <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-view]" value="1"></td>
                                    <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-delete]" value="1"></td>
                                    <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-list]" value="1"></td>

                                </tr>

                            @endforeach
                            </tbody>

                        </table>

                        <div class="text-center">
                            <button class="btn btn-primary" type="submit">Submit</button>

                            <div class="float-end">
                                <a href="{{route('permissions.index')}}">
                                    <span class="badge bg-secondary">Back</span>
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
        </form>
            </div>

    </div>
</div>





@endsection
