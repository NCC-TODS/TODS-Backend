@extends('dashboard.layout');

@section('content')
    <section class="col-lg-12">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                    <i class="fa fa-tag mr-1"></i>
                    مدیریت نسخه
                </h3>
                <ul class="nav nav-pills mr-auto p-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="#list-users" data-toggle="tab">لیست نسخه‌های اپلیکیشن</a>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content p-0">
                    <div class="tab-pane active" id="new-version">


                        {{-- Upload new client version --}}
                        <form action="{{ route('dashboard.clvm.upload') }}" method="POST" enctype="multipart/form-data"
                            class="form-group mb-5">
                            @csrf

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <h5><i class="icon fa fa-check"></i>ثبت شد</h5>
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <h5><i class="icon fa fa-check"></i>مشکل پیش آمد!</h5>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <label for="exampleInputFile">بارگذاری نسخه جدید</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="apk" accept=".apk" class="custom-file-input" required>
                                    <label class="custom-file-label" for="exampleInputFile">انتخاب فایل</label>
                                </div>
                                <div class="input-group-append">
                                    <input type="text" name="version" class="input-group-text" placeholder="VERSION"
                                        required>
                                </div>

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">ثبت</button>
                                </div>
                            </div>
                        </form>


                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.querySelectorAll('.custom-file-input').forEach(function(input) {
                                    input.addEventListener('change', function(e) {
                                        let fileName = e.target.files[0]?.name || 'انتخاب فایل';
                                        e.target.nextElementSibling.innerText = fileName;
                                    });
                                });
                            });
                        </script>


                    </div>

                    <div class="tab-pane active" id="list-versions">
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
