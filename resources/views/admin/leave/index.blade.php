@extends('admin.layouts.master')

@section('content')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-11">

                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{Session::get('message')}}
                    </div>
                @endif
                
                <div class="card">
                    <div class="card-header"><strong>{{ __('Leaves') }}</strong></div>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Date From</th>
                            <th scope="col">Date To</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Reply</th>
                            <th scope="col">Approve/Reject</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leaves as $key=>$leave)
                            <tr>
                                <td>{{$leave->user->name}}</td>
                                <td>{{$leave->from}}</td>
                                <td>{{$leave->to}}</td>
                                <td>{{$leave->type}}</td>
                                <td><div style="width: 200px; max-height: 100px;">{{$leave->description}}</div></td>
                                <td>
                                    @if($leave->status == 0)
                                        <div class="badge bg-secondary">Pending</div>
                                    @elseif ($leave->status == 1)
                                        <div class="badge bg-success">Approved</div>
                                    @elseif ($leave->status == 2)
                                        <div class="badge bg-danger">Rejected</div>
                                    @endif
                                </td>
                                <td>{{$leave->message}}</td>
                                <td>
                                    @if($leave->status == 0)
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$leave->id}}">
                                            <div class="p-1">
                                                <button class="btn btn-info"> Approve/Reject</button>
                                            </div>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$leave->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{route('accept-reject-leave', $leave->id)}}" method="post">
                                                    @csrf

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirm Leave</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="">Select</option>
                                                                    <option value="1">Approve</option>
                                                                    <option value="2">Reject</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mt-1">
                                                                <label>Reply message</label>
                                                                <textarea class="form-control mt-1 " placeholder="Reply..." name="message"></textarea>
                                                            </div>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-success">Confirm</button>
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


                </div>
            </div>
        </div>
    </div>
@endsection
