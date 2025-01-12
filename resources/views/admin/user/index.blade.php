@extends('admin.layouts.master')

@section('content')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-10">

                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{Session::get('message')}}
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        All Employees
                    </div>
                    <div class="card-body">

                        @if(count($users)>0)

                            <table id="datatablesSimple">
                                <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Start Date</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>Edit</th>
                                    <th>Delete</th>

                                </tr>
                                </thead>

                                <tbody>

                                @foreach($users as $key=>$user)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <img src="{{url(asset('profile/'.$user->image))}}" alt="Profile"
                                                 height="100">
                                        </td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->role->name}}</td>
                                        <td><span class="badge bg-success">{{$user->department->name}}</span></td>
                                        <td>{{$user->designation}}</td>
                                        <td>{{$user['start_from']}}</td>
                                        <td class="overflow-clip">{{$user->address}}</td>
                                        <td>{{$user->mobile_number}}</td>

                                        <td>
                                            <a href="{{route('users.edit', $user->id)}}">
                                                <div class="p-1">
                                                    <i class="fas fa-edit"></i>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal"
                                               data-bs-target="#exampleModal{{$user->id}}">
                                                <div class="p-1">
                                                    <i class="fas fa-trash"></i>
                                                </div>
                                            </a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal{{$user->id}}" tabindex="-1"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form action="{{route('users.destroy', $user->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Confirm
                                                                    Delete</h5>
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Do you really want to delete?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- Modal End -->
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>

                            </table>

                        @else
                            No employee found!
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
