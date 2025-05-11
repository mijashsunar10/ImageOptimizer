<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function create()
    {
        return view('images.create');
    }

    public function index()
    {
      
        $images = Image::latest()->paginate(12);
        return view('images.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB
            'title' => 'nullable|string|max:255',
        ]);

        $uploadedFile = $request->file('image');
        $path = $uploadedFile->store('images', 'public');

        // Further optimize the image
        $image = $this->imageManager->read($uploadedFile->getPathname());
        
        // Resize if needed (maintain aspect ratio)
        $image->scale(width: 1200);
        
        // Adjust quality based on original size
        $quality = $uploadedFile->getSize() > 5 * 1024 * 1024 ? 65 : 80;
        
        // Save with optimized quality
        $image->save(storage_path('app/public/'.$path), quality: $quality);

        // Create database record
        $imageModel = Image::create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'title' => $request->title,
            'path' => $path,
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => filesize(storage_path('app/public/'.$path)),
            'width' => $image->width(),
            'height' => $image->height(),
        ]);

        return response()->json([
            'redirect' => route('images.show', $imageModel),
            'message' => 'Image uploaded and optimized successfully!'
        ]);
    }

    public function show(Image $image)
    {
        return view('images.show', compact('image'));
    }
}