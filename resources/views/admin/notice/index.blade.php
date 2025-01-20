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

                <div class="alert alert-secondary">All Notices</div>

                @foreach($notices as $notice)

                    <div class="card mb-3 alert alert-primary">

                        <div class="card-header m-2 bg-warning">
                            {{$notice->title}}
                        </div>

                        <div class="card-body">

                            <div class="alert">{{$notice->description}}</div>
                            <div class="badge bg-success">{{$notice->date}}</div>
                            <div class="badge bg-warning text-black">{{$notice->name}}</div>
                        </div>

                        @if(isset(auth()->user()->role->permission['name']['notice']['can-edit']))
                        <div class="card-footer">
                            <div class="float-start">
{{--                                @if(isset(auth()->user()->role->permission['name']['notice']['can-edit']))--}}
                                <a href="{{route('notices.edit', $notice->id)}}">
                                    <div class="p-1">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                </a>
{{--                                @endif--}}
                            </div>
                            <div class="float-end">
{{--                                @if(isset(auth()->user()->role->permission['name']['notice']['can-delete']))--}}
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$notice->id}}">
                                        <div class="p-1">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                    </a>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal{{$notice->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{route('notices.destroy', $notice->id)}}" method="post">
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
{{--                                @endif--}}
                            </div>

                        </div>
                        @endif

                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
