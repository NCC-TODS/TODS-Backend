<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login page
     */
    public function show_login()
    {
        return view('auth.login');
    }

    /**
     * Get the phone number and send OTP SMS
     */
    public function get_phone(Request $request)
    {

        // اعتبارسنجی ورودی
        $request->validate([
            'phone' => 'required|string|size:11|regex:/^09[0-9]{9}$/',
        ]);

        // پیدا کردن کاربر بر اساس شماره تلفن
        $user = User::where('phone_number', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'کاربری با این شماره یافت نشد.']);
        }

        // check if the previuous code is still valid (e.g., within 2 minutes)
        if ($user->verification_code && $user->updated_at->gt(now()->subMinutes(2))) {
            return back()->withErrors(['phone' => 'لطفا ۲ دقیقه دیگر مجددا تلاش فرمایید.']);
        }

        // تولید کد تأیید (۴ یا ۵ رقمی)
        $code = rand(1000, 9999);

        $user->verification_code = $code;
        $user->save();

        //ارسال پیامک (فعلاً شبیه‌سازی شده)
        // sendSMS

        session(['phone' => $user->phone_number]);

        // هدایت به صفحه‌ی وارد کردن کد
        return redirect()->route('auth.showverifycode');
    }


    /**
     * Show the verify code page
     */
    public function show_verify_code()
    {
        return view('auth.verifycode', [
            'status' => session('status')
        ]);
    }

    /**
     * Verify the code and do Login
     */
    public function verify_code(Request $request)
    {
        $request->validate([
            'code' => 'required|digits_between:4,6',
        ]);

        // شماره تلفن از سشن گرفته میشه
        $phone = session('phone');

        if (!$phone) {
            return redirect()->route('auth.login')->with(['status' => 'سشن شما منقضی شده است.']);
        }


        $user = User::where('phone_number', $phone)->first();

        if (!$user) {
            return redirect()->route('auth.showverifycode')->with('status', 'کاربر مورد نظر وجود ندارد!');
        }

        if ($user->verification_code !== $request->code) {
            return redirect()->route('auth.showverifycode')->with('status', 'کد وارد شده اشتباه است.');
        }

        $user->verification_code = null;
        $user->save();

        Auth::login($user);

        // پاک‌سازی سشن بعد از ورود موفق
        session()->forget('phone');

        return redirect()->route('dashboard.main')->with('success', 'ورود با موفقیت انجام شد.');
    }


    // Log the user Out
    public function logout(Request $request)
    {
        // اگه با session هم وارد شده بود (مثلاً از وب)
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('status', 'با موفقیت خارج شدید.');
    }
}
