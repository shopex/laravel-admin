@extends('admin::layout')

@section('title', 'Edit Permission')

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

        {!! Form::model($permission, [
            'method' => 'PATCH',
            'url' => ['/admin/permissions', $permission->id],
            'class' => 'form-horizontal'
        ]) !!}

        @include ('admin::permissions.form', ['submitButtonText' => 'Update'])

        {!! Form::close() !!}
    </div>
@endsection