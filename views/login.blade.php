@extends('index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Авторизация</h1>
            <form action="{{ SITE_URL }}login" method="POST">
                <input type="hidden" name="validationRules" value="{{ json_encode($validationRules) }}">
                <div class="form-group">
                    <label for="login">Ваш логин</label>
                    <input type="text" class="form-control" id="login" name="login">
                    <span class="help-block"></span>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <span class="help-block"></span>
                </div>
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </div>
@endsection
