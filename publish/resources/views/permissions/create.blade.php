@extends('admin::layout')

@section('title', 'Create Permission')

@section('action-bar')
    <a href="{{ url('/admin/permissions') }}" title="Back">
        <button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
    </a>
@endsection

@section('content')
    <div class="container">
        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        {!! Form::open(['url' => '/admin/permissions', 'class' => 'form-horizontal']) !!}

        @include ('admin::permissions.form')

        {!! Form::close() !!}
    </div>
@endsection