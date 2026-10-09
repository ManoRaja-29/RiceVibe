<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Support\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $q = Media::query()->latest();
        if ($t = trim((string) $request->query('q'))) {
            $q->where('original_name', 'like', "%$t%");
        }

        return view('admin.media', ['items' => $q->paginate(24)->withQueryString()]);
    }

    /** Handles both the library form and the rich-text editor (JSON). */
    public function store(Request $request, MediaService $service)
    {
        $request->validate([
            'files' => 'required_without:file|array|max:20',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
            'file' => 'required_without:files|file|mimes:jpg,jpeg,png,webp|max:5120',
            'alt' => 'nullable|string|max:200',
        ]);

        $saved = [];
        $files = $request->hasFile('file') ? [$request->file('file')] : $request->file('files', []);
        foreach ($files as $file) {
            try {
                $saved[] = $service->store($file, $request->input('alt'));
            } catch (\Throwable) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Image could not be processed.'], 422);
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['url' => $saved[0]->url ?? null]);
        }

        return back()->with('status', count($saved).' file(s) uploaded.');
    }

    public function update(Request $request, Media $medium)
    {
        $medium->update($request->validate(['alt' => 'nullable|string|max:200']));

        return back()->with('status', 'Alt text saved.');
    }

    public function destroy(Media $medium, MediaService $service)
    {
        $service->delete($medium);

        return back()->with('status', 'File deleted.');
    }
}
