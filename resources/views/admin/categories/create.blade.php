@extends('admin.layout.master')

@section('title', isset($category) ? 'Edit Category' : 'Add New Category')

@section('body')
    <!-- Page header -->
    <div class="page-header d-lg-flex d-block">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1"><a href="{{ route('admin.categories.index') }}" class="text-decoration-none">Categories</a></li>
            <li class="breadcrumb-item1 active">{{ isset($category) ? 'Edit' : 'Add' }} Category</li>
        </ol>
    </div>
    <!-- End Page header -->

    <div class="row">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ isset($category) ? 'Edit' : 'Add' }} Category Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($category))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <!-- Category Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $category->name ?? '') }}" 
                                       placeholder="Enter category name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Parent Category -->
                            <div class="col-md-6 mb-3">
                                <label for="parent_id" class="form-label">Parent Category</label>
                                <select class="form-control @error('parent_id') is-invalid @enderror" 
                                        id="parent_id" 
                                        name="parent_id">
                                    <option value="">None (Main Category)</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" 
                                                {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Enter category description">{{ old('description', $category->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Category Image</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <small class="form-text text-muted">Allowed: JPG, PNG, GIF (Max: 2MB)</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Preview -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Image Preview</label>
                                <div id="imagePreview" class="border rounded p-2 text-center" style="min-height: 100px;">
                                    @if(isset($category) && $category->image)
                                        <img src="{{ $category->image }}" alt="{{ $category->name }}" class="img-fluid" style="max-height: 150px;">
                                    @else
                                        <p class="text-muted mb-0">No image selected</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Sort Order -->
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $category->sort_order ?? 0) }}" 
                                       min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="status" 
                                           name="status" 
                                           value="1"
                                           {{ old('status', $category->status ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">Active</label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather feather-save"></i> {{ isset($category) ? 'Update' : 'Create' }} Category
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                    <i class="feather feather-x"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid" style="max-height: 150px;">';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '<p class="text-muted mb-0">No image selected</p>';
            }
        }
    </script>
@endsection
