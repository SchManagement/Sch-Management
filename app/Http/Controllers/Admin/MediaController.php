<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaUploadRequest;
use App\Models\Media;
use App\Support\PublicMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MediaController extends Controller
{
    public function index(Request $request): Response
    {
        $media = Media::query()
            ->with('uploader:id,name')
            ->when($request->string('search')->toString(), function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('filename', 'like', "%{$search}%")->orWhere('original_name', 'like', "%{$search}%")
                        ->orWhere('path', 'like', "%{$search}%");
                });
            })
            ->when($request->string('category')->toString(), fn ($query, $category) => $query->where('category', $category))
            ->when($request->string('mime')->toString(), fn ($query, $mime) => $query->where('mime_type', 'like', "{$mime}%"))
            ->latest()
            ->paginate(18)
            ->withQueryString()
            ->through(fn (Media $item): array => [
                'id' => $item->id,
                'path' => $item->path,
                'filename' => $item->filename,
                'original_name' => $item->original_name,
                'mime_type' => $item->mime_type,
                'extension' => $item->extension,
                'size' => $item->size,
                'width' => $item->width,
                'height' => $item->height,
                'alt_text' => $item->alt_text,
                'caption' => $item->caption,
                'category' => $item->category,
                'url' => PublicMedia::url($item->path),
                'uploader' => $item->uploader,
                'created_at' => $item->created_at,
            ]);

        $categories = collect(['general', 'projects', 'articles', 'services', 'testimonials', 'company'])
            ->merge(Media::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'))
            ->filter(fn ($category): bool => is_string($category) && trim($category) !== '')
            ->unique()
            ->values();

        return Inertia::render('Admin/Media/Index', [
            'media' => $media,
            'filters' => $request->only(['search', 'category', 'mime']),
            'categories' => $categories,
        ]);
    }

    public function store(MediaUploadRequest $request): RedirectResponse
    {
        $file = $request->file('image');
        $mimeType = (string) $file->getMimeType();
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (! isset($extensions[$mimeType])) {
            throw ValidationException::withMessages([
                'image' => 'Isi file harus berupa gambar JPG, JPEG, PNG, atau WebP yang valid.',
            ]);
        }

        $extension = $extensions[$mimeType];
        $filename = Str::uuid()->toString().'.'.$extension;
        $category = $request->string('category')->toString();
        $directory = 'media/'.(Str::slug($category) ?: 'general').'/'.now()->format('Y/m');
        $dimensions = @getimagesize($file->getRealPath());
        $disk = Storage::disk('public');
        $path = $disk->putFileAs($directory, $file, $filename);

        if (! is_string($path) || $path === '') {
            throw ValidationException::withMessages([
                'image' => 'Gambar gagal disimpan. Periksa folder dan permission penyimpanan.',
            ]);
        }

        try {
            Media::query()->create([
                'disk' => 'public',
                'path' => $path,
                'filename' => $filename,
                'original_name' => Str::limit(basename(str_replace('\\', '/', $file->getClientOriginalName())), 255, ''),
                'mime_type' => $mimeType,
                'extension' => $extension,
                'size' => $file->getSize(),
                'width' => is_array($dimensions) ? $dimensions[0] : null,
                'height' => is_array($dimensions) ? $dimensions[1] : null,
                'category' => $category,
                'uploaded_by' => $request->user()->getAuthIdentifier(),
                'alt_text' => $request->input('alt_text'),
                'caption' => $request->input('caption'),
            ]);
        } catch (Throwable $exception) {
            $disk->delete($path);

            throw $exception;
        }

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil diunggah.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        $path = $this->safeRelativePath($media->path);
        $canDeleteFile = $media->disk === 'public'
            && $path !== null
            && str_starts_with($path, 'media/');

        if ($canDeleteFile) {
            try {
                Storage::disk('public')->delete($path);
            } catch (Throwable $exception) {
                report($exception);

                return back()->with('error', 'File media gagal dihapus. Record belum diubah.');
            }
        }

        $media->delete();

        return redirect()->route('admin.media.index')->with(
            $canDeleteFile ? 'success' : 'warning',
            $canDeleteFile
                ? 'Media berhasil dihapus.'
                : 'Record media dihapus, tetapi file fisik dilewati karena path atau disk tidak valid.',
        );
    }

    private function safeRelativePath(?string $path): ?string
    {
        $path = str_replace('\\', '/', trim((string) $path));

        if ($path === '' || str_starts_with($path, '/') || str_contains($path, "\0")
            || str_contains($path, ':') || str_contains($path, '//')) {
            return null;
        }

        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..' || str_starts_with($segment, '.')) {
                return null;
            }
        }

        return $path;
    }
}
