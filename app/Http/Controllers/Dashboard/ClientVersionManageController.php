<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientVersionManageController extends Controller
{
    // Show upload file form
    public function show() {
        return view('dashboard.versionmanage.upload-file');
    }
}
