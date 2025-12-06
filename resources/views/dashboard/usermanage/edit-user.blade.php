@extends('dashboard.layout');

@section('content')
    <section class="col-lg-12">
                        <form action="{{ route('dashboard.usermanage.update-user', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">نام</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
                            </div>
                            <div class="form-group">
                                <label for="last_name">نام خانوادگی</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $user->last_name }}">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">شماره تلفن</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $user->phone_number }}">
                            </div>
                            <div class="form-group">
                                <label for="state">استان</label>
                                <input type="text" name="state" id="state" class="form-control" value="{{ $user->state }}">
                            </div>
                            <div class="form-group">
                                <label for="city">شهر</label>
                                <input type="text" name="city" id="city" class="form-control" value="{{ $user->city }}">
                            </div>
                            <div class="form-group">
                                <label for="organization">سازمان</label>
                                <input type="text" name="organization" id="organization" class="form-control" value="{{ $user->organization }}">
                            </div>
                            <button type="submit" class="btn btn-primary">ویرایش</button>
                        </form>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>

    </section>
@endsection
