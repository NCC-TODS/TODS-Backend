@extends('dashboard.layout');

@section('content')
    <section class="col-lg-12">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                    <i class="fa fa-pie-chart mr-1"></i>
                    ورژن منیجر
                </h3>
                <ul class="nav nav-pills mr-auto p-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="#list-users" data-toggle="tab">لیست نسخه‌های اپلیکیشن</a>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content p-0">
                    <div class="tab-pane active" id="list-users">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ورژن</th>
                                    <th>دانلود</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>

    </section>
@endsection
