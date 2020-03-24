@extends('index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Форма редактирования задачи</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form role="form" action="" method="POST">
                        <input type="hidden" name="validationRules" value="{{ json_encode($validationRules) }}">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $task->name }}">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{ $task->email }}">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label>Задача</label>
                            <textarea class="form-control" rows="3" name="body">{{ $task->body }}</textarea>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label>Статус</label>
                            <select class="form-control" name="active">
                                <option @if ($task->active) selected @endif value="1">выполнено</option>
                                <option @if (!$task->active) selected @endif value="0">не выполнено</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Опубликовать</button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection
