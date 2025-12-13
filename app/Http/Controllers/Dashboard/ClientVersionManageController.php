<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientVersionManageController extends Controller
{
    // نمایش لیست
    public function index()
    {
        $versions = ClientVersion::orderByDesc('created_at')->get();

        return view('dashboard.versionmanage.upload-file', compact('versions'));
    }

    // آپلود
    public function store(Request $request)
    {
        $request->validate([
            'version' => 'required|string|unique:client_versions,version',
            'apk' => 'required|file|mimes:apk,zip',
        ]);

        $fileName = 'nrp-' . $request->version . '.apk';

        $path = $request->file('apk')->storeAs(
            'apks',
            $fileName,
            'public'
        );

        ClientVersion::create([
            'version'   => $request->version,
            'file_path' => $path,
            'is_active' => false,
        ]);

        return back()->with('success', 'نسخه با موفقیت آپلود شد');
    }

    // فعال کردن نسخه
    public function activate(ClientVersion $clientVersion)
    {
        ClientVersion::where('is_active', true)->update(['is_active' => false]);

        $clientVersion->update(['is_active' => true]);

        return back()->with('success', 'این نسخه فعال شد');
    }

    // حذف نسخه + فایل
    public function destroy(ClientVersion $clientVersion)
    {
        Storage::disk('public')->delete($clientVersion->file_path);

        $clientVersion->delete();

        return back()->with('success', 'نسخه حذف شد');
    }
}
