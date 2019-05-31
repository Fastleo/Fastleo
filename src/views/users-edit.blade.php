@extends('fastleo::app')

@section('content')

    <div class="row">
        <div class="col">
            <h3>Пользователи / @if(isset($user->id)){{ 'Редактировать' }}@else{{ 'Создать' }}@endif запись</h3>
            @if(session()->has('message'))
                <div class="alert alert-success">{{ session()->get('message') }}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col">
            &nbsp;
        </div>
    </div>
    <div class="row">
        <div class="col">
            <form action="" method="post">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="name">Логин:</label>
                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Логин" value="{{ $user->name ?? '' }}" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="email">Email:</label>
                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ $user->email ?? '' }}" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="password">Пароль:</label>
                    <div class="col-sm-7">
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Пароль храниться в зашифрованном виде">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label class="form-check-label" for="fastleo_admin">Администратор:</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="hidden" name="fastleo_admin" value="0">
                            <input type="checkbox" name="fastleo_admin" id="fastleo_admin" class="form-check-input" value="1" @if(isset($user->fastleo_admin) and $user->fastleo_admin == 1){{ 'checked' }}@endif>
                        </div>
                    </div>
                </div>
                <hr>
                <input type="submit" class="btn btn-success" value="Сохранить">
                <input type="submit" name="id" class="btn btn-warning" value="Сохранить и выйти">
                <a href="{{ route('fastleo.users') . '?' . request()->getQueryString() }}" class="btn btn-primary">Вернуться</a>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            &nbsp;<br><br>
        </div>
    </div>

@endsection