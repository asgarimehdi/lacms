<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $file = $request->file('image');

        if (! $file) {
            abort(400, 'No file provided');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        if (! in_array($file->getMimeType(), $allowed, true)) {
            abort(422, 'Invalid file type');
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            abort(422, 'File too large (max 2MB)');
        }

        $path = $file->store('uploads', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/'.$path),
        ]);
    }
}
