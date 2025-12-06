@extends('auth.layout')

@section('form')
    <div class="overlay"></div>
    <form action="{{ route('auth.getphone') }}" method="post" class="form-group">
        @csrf
        <img src="{{ asset('assets/imgs/ncc-logo-white.png') }}" alt="Logo" class="ncc-logo" />
        <h1 class="title">ورود به حساب کاربری</h1>
        <input type="tel" name="phone" placeholder="شماره تلفن" required class="input_data" />
        <button type="submit" class="submit_button">دریافت کد</button>

        @error('phone')
            <span class="error">
                {{ $message }}
            </span>
        @enderror
    </form>
@endsection
