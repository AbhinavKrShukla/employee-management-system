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
                    <div class="card-header">{{ __('Update Leave') }}</div>

                    <div class="card-body">

                        <form method="post" action="{{route('leaves.update', $leave->id)}}"> @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>From</label>
                                <input id="datepicker" class="form-control @error('from') is-invalid @enderror" name="from" value="{{$leave->from}}">
                                @error('from')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>To</label>
                                <input id="datepicker1" class="form-control @error('to') is-invalid @enderror" name="to" value="{{$leave->to}}">
                                @error('to')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Type of Leave</label>
                                <select class="form-control" name="type">
                                    <option value="annualleave" @if($leave->type == "annualleave") selected @endif>Annual Leave</option>
                                    <option value="sickleave" @if($leave->type == "sickleave") selected @endif>Sick Leave</option>
                                    <option value="parentalleave" @if($leave->type == "parentalleave") selected @endif>Parental Leave</option>
                                    <option value="other" @if($leave->type == "other") selected @endif>Other Leave</option>
                                </select>
                                @error('type')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description">{{$leave->description}}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group text-center mt-2">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>

                        </form>

                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection
