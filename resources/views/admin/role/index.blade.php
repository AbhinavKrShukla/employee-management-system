@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
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
                        All Roles
                    </div>
                    <div class="card-body">

                        @if(count($roles)>0)

                            <table id="datatablesSimple">
                                <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Edit</th>
                                    <th>Delete</th>

                                </tr>
                                </thead>

                                <tbody>

                                @foreach($roles as $key=>$role)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$role->name}}</td>
                                        <td>{{$role->description}}</td>
                                        <td>
                                            @if(isset(auth()->user()->role->permission['name']['role']['can-edit']))
                                            <a href="{{route('roles.edit', $role->id)}}">
                                                <div class="p-1">
                                                    <i class="fas fa-edit"></i>
                                                </div>
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset(auth()->user()->role->permission['name']['role']['can-delete']))
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$role->id}}">
                                                <div class="p-1">
                                                    <i class="fas fa-trash"></i>
                                                </div>
                                            </a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal{{$role->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form action="{{route('roles.destroy', $role->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Do you really want to delete?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- Modal End -->
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach

                                </tbody>

                            </table>

                        @else
                            No roles found!
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
