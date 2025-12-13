<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientVersionManageController extends Controller
{
    // Show upload file form
    public function show()
    {
        return view('dashboard.versionmanage.upload-file');
    }


    // Upload new Client Version
    public function upload(Request $request)
    {
        $request->validate([
            'version' => 'required|string',
            'apk' => 'required|file|mimes:apk,zip',
        ]);

        $version = $request->input('version');
        $fileName = 'nrp-' . $version . '.apk';

        $request->file('apk')->storeAs(
            'public/apks',
            $fileName
        );

        return back()->with('success', 'APK uploaded successfully');
    }
}
