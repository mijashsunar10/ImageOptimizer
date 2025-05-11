@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Image Gallery</h1>
            <a href="{{ route('images.create') }}" class="btn btn-primary">Upload New Image</a>
        </div>

        @if($images->isEmpty())
            <div class="alert alert-info">No images found. Upload your first image!</div>
        @else
            <div class="row">
                @foreach($images as $image)
                    <div class="col-md-4 mb-4">
                        <div class="card image-card h-100">
                            <a href="{{ route('images.show', $image) }}">
                                <img src="{{ asset('storage/'.$image->path) }}" 
                                class="card-img-top image-thumbnail" 
                                alt="{{ $image->title ?? 'Image' }}"
                                onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text=Image+Not+Found';">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">{{ $image->title ?? 'Untitled' }}</h5>
                                <p class="card-text text-muted small">
                                    {{ $image->width }}x{{ $image->height }}px | 
                                    {{ round($image->size / 1024) }}KB
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $images->links() }}
            </div>
        @endif
    </div>
@endsection