@extends('dashboard.layout');

@section('content')
    <section class="col-lg-12">
                        <form action="{{ route('dashboard.usermanage.create-user-action') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="name">نام</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="last_name">نام خانوادگی</label>
                                <input type="text" name="last_name" id="last_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">شماره تلفن</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="state">استان</label>
                                <input type="text" name="state" id="state" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="city">شهر</label>
                                <input type="text" name="city" id="city" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="organization">سازمان</label>
                                <input type="text" name="organization" id="organization" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="role">نقش</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">انتخاب نقش</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">ایجاد</button>
                        </form>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>

    </section>
@endsection
