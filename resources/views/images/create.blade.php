<!-- resources/views/images/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Upload Image</h1>
    <form id="uploadForm" method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label for="image" class="form-label">Image (up to 10MB)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            <div class="form-text">We'll automatically compress your image</div>
        </div>
        
        <div class="mb-3">
            <label for="title" class="form-label">Title (Optional)</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        
        <div class="progress mb-3 d-none">
            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
        </div>
        
        <button type="submit" class="btn btn-primary">Upload Image</button>
    </form>
</div>

<script>
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('image');
    const file = fileInput.files[0];
    const progressDiv = document.querySelector('.progress');
    const progressBar = document.getElementById('progressBar');
    
    if (!file) return;
    
    // Show progress bar
    progressDiv.classList.remove('d-none');
    progressBar.style.width = '0%';
    progressBar.textContent = '0%';
    
    try {
        // Compress image if >2MB
        if (file.size > 2 * 1024 * 1024) {
            progressBar.textContent = 'Compressing...';
            const compressedFile = await compressImage(file);
            
            // Replace original file with compressed version
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(compressedFile);
            fileInput.files = dataTransfer.files;
            
            progressBar.style.width = '50%';
            progressBar.textContent = '50% - Uploading...';
        }
        
        // Proceed with form submission
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
        });
        
        if (response.ok) {
            window.location.href = await response.json().then(data => data.redirect);
        } else {
            throw new Error('Upload failed');
        }
    } catch (error) {
        alert('Error: ' + error.message);
        progressBar.classList.add('bg-danger');
    }
});

async function compressImage(file, maxWidth = 1024, quality = 0.8) {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.src = event.target.result;
            
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                // Calculate new dimensions
                let width = img.width;
                let height = img.height;
                
                if (width > maxWidth) {
                    height = (maxWidth / width) * height;
                    width = maxWidth;
                }
                
                canvas.width = width;
                canvas.height = height;
                
                // Draw and compress
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob((blob) => {
                    const compressedFile = new File([blob], file.name, {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    resolve(compressedFile);
                }, 'image/jpeg', quality);
            };
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endsection