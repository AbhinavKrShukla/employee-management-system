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

                <div class="card border-info">
                    <div class="card-header">{{ __('Leave Information') }}</div>

                    <div class="card-body">

                        <form method="post" action="{{route('leaves.store')}}"> @csrf

                            <div class="form-group">
                                <label>From</label>
                                <input id="datepicker" class="form-control @error('from') is-invalid @enderror" name="from" value="{{old('from')}}">
                                @error('from')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>To</label>
                                <input id="datepicker1" class="form-control @error('to') is-invalid @enderror" name="to" value="{{old('to')}}">
                                @error('to')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Type of Leave</label>
                                <select class="form-control" name="type">
                                    <option value="annualleave">Annual Leave</option>
                                    <option value="sickleave">Sick Leave</option>
                                    <option value="parentalleave">Parental Leave</option>
                                    <option value="other">Other Leave</option>
                                </select>
                                @error('type')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description">{{old('description')}}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group text-center mt-2">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>

                <!-- All the Leaves  -->
                <div class="card mt-3">
                    <div class="card-header m-2"><strong>My Leaves</strong></div>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date From</th>
                            <th scope="col">Date To</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Reply</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leaves as $key=>$leave)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$leave->from}}</td>
                                <td>{{$leave->to}}</td>
                                <td>{{$leave->type}}</td>
                                <td>{{$leave->description}}</td>
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
                                        <a href="{{route('leaves.edit', $leave->id)}}">
                                            <div class="p-1">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->status == 0)
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$leave->id}}">
                                            <div class="p-1">
                                                <i class="fas fa-trash" style="color: red"></i>
                                            </div>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$leave->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{route('leaves.destroy', $leave->id)}}" method="post">
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
                </div>
            </div>
        </div>
    </div>
@endsection
