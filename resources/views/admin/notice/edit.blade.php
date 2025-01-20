@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{Session::get('message')}}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">{{ __('Create Update') }}</div>

                    <div class="card-body">

                        <form method="post" action="{{route('notices.update', $notice->id)}}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{$notice->title}}">
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description">{{$notice->description}}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control @error('date') is-invalid @enderror" id="datepicker"
                                       name="date"
                                       value="{{$notice->date}}">
                                @error('date')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label>Name</label>--}}
{{--                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{$notice->name}}">--}}
{{--                                @error('name')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}


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
