@extends('dashboard.layout');

@section('content')
    <section class="col-lg-12">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                    <i class="fa fa-pie-chart mr-1"></i>
                    مدیریت کاربران
                </h3>
                <ul class="nav nav-pills mr-auto p-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="#list-users" data-toggle="tab">لیست کاربران</a>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content p-0">
                    <div class="tab-pane active" id="list-users">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>نام خانوادگی</th>
                                    <th>شماره تلفن</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>
                                            <a href="{{ route('dashboard.usermanage.edit-user', $user->id) }}" class="btn btn-primary">ویرایش</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>

    </section>
@endsection
