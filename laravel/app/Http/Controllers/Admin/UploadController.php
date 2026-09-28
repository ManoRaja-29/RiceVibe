<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', File::image()->max(8 * 1024)],
            'type' => ['required', 'in:banner,gallery,media,product,certificate'],
            'title' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'product_slug' => ['nullable', 'string', 'max:180'],
        ]);

        $path = $request->file('file')->store('uploads/'.$validated['type'], 'public');
        $upload = Upload::create([
            'type' => $validated['type'],
            'title' => $validated['title'] ?? null,
            'category' => $validated['category'] ?? null,
            'product_slug' => $validated['product_slug'] ?? null,
            'path' => $path,
            'disk' => 'public',
        ]);

        return back()->with('status', 'Upload saved successfully.')->with('upload_id', $upload->id);
    }

    public function destroy(Upload $upload)
    {
        Storage::disk($upload->disk)->delete($upload->path);
        $upload->delete();
        return back()->with('status', 'Upload deleted successfully.');
    }
}
