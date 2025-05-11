<!-- resources/views/images/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>{{ $image->title ?? 'Image Details' }}</h2>
            <a href="{{ route('images.create') }}" class="btn btn-primary">Upload Another</a>
        </div>
        
        <div class="card-body">
            <div class="text-center mb-4">
                <img src="{{ asset('storage/'.$image->path) }}" 
                class="card-img-top image-thumbnail" 
                alt="{{ $image->title ?? 'Image' }}"
                onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text=Image+Not+Found';">
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Original Name:</strong> {{ $image->original_name }}</p>
                    <p><strong>Uploaded:</strong> {{ $image->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Dimensions:</strong> {{ $image->width }}×{{ $image->height }}px</p>
                    <p><strong>Size:</strong> {{ round($image->size / 1024) }} KB</p>
                    <p><strong>Type:</strong> {{ $image->mime_type }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection