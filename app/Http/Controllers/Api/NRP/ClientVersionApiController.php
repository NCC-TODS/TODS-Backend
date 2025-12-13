<?php

namespace App\Http\Controllers\Api\NRP;

use App\Http\Controllers\Controller;
use App\Models\ClientVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ClientVersionApiController extends Controller
{
    // JSON آخرین نسخه فعال
    public function latest()
    {
        $version = ClientVersion::where('is_active', true)->first();

        if (! $version) {
            return response()->json([
                'message' => 'No active version found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'version' => $version->version,
            // 'download_url' => Storage::url($version->file_path),
            // 'updated_at' => $version->updated_at,
        ]);
    }

    // دانلود آخرین نسخه فعال
    public function download()
    {
        $version = ClientVersion::where('is_active', true)->first();

        if (! $version || ! Storage::disk('public')->exists($version->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download(
            $version->file_path,
            'nrp-' . $version->version . '.apk'
        );
    }
}
