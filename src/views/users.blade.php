@extends('fastleo::app')

@section('content')

    <div class="row">
        <div class="col-2">
            <h3>Пользователи</h3>
        </div>
        <div class="col-6 text-right">
            
        </div>
        <div class="col-4 text-right">
            <a href="{{ route('fastleo.users.add') . '?' . request()->getQueryString() }}" class="btn btn-success">Добавить пользователя</a>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <br>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Логин</th>
                    <th>Email</th>
                    <th>Регистрация</th>
                    <th width="80"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><a href="{{ route('fastleo.users.edit', ['id' => $user->id]) . '?' . request()->getQueryString() }}">{{ $user->name ?? '' }}</a></td>
                        <td><a href="{{ route('fastleo.users.edit', ['id' => $user->id]) . '?' . request()->getQueryString() }}">{{ $user->email ?? '' }}</a></td>
                        <td><a href="{{ route('fastleo.users.edit', ['id' => $user->id]) . '?' . request()->getQueryString() }}">{{ $user->created_at ?? '' }}</a></td>
                        <td>
                            <a href="{{ route('fastleo.users.edit', ['id' => $user->id]) . '?' . request()->getQueryString() }}"><i class="far fa-edit"></i></a>
                            <a href="{{ route('fastleo.users.delete', ['id' => $user->id]) . '?' . request()->getQueryString() }}" onclick="return confirm('Удалить пользователя?');"><i class="far fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            {{ $users->appends(request()->all())->links() }}
        </div>
    </div>

@endsection