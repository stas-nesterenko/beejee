@extends('index')

@section('content')
    <link rel="stylesheet" href="/vendor/almasaeed2010/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Имя пользователя</th>
                            <th>Email</th>
                            <th>Текст задачи</th>
                            <th>Статус</th>
                            @if (BJ\Auth::getInstance()->ifLogged())
                                <th>Отредактировано администратором</th>
                                <th></th>
                            @endif
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Форма добавления задачи</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form role="form" action="{{ SITE_URL }}tasks/add" method="POST">
                        <input type="hidden" name="validationRules" value="{{ json_encode($validationRules) }}">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label>Задача</label>
                            <textarea class="form-control" rows="3" name="body"></textarea>
                            <span class="help-block"></span>
                        </div>
                        <button type="submit" class="btn btn-primary">Опубликовать</button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    <div class="modal modal-success fade" id="modal-success">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p></p>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script src="/vendor/almasaeed2010/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/vendor/almasaeed2010/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '/',
                    type: 'post'
                 },
                "paging": true,
                "lengthChange": true,
                "lengthMenu": [ 3, 6, 9],
                "searching": false,
                "ordering": true,
                "info": true,
                "columns": {!! json_encode($columns)!!},
                "autoWidth": false,
                'stateSave': true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
                }
            });
        });
    </script>
@endsection
