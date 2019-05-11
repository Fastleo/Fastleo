@extends('fastleo::app')

@section('content')

    <form action="{{ route('fastleo') }}" method="post" class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>

@endsection