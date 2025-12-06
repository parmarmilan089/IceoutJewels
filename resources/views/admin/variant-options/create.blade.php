@extends('admin.layout.master')

@section('title', isset($option) ? 'Edit Variant Option' : 'Add Variant Option')

@section('body')
    <div class="page-header d-lg-flex d-block">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1"><a href="{{ route('admin.variant-options.index') }}" class="text-decoration-none">Variant Options</a></li>
            <li class="breadcrumb-item1 active">{{ isset($option) ? 'Edit' : 'Add' }} Option</li>
        </ol>
    </div>

    <form action="{{ isset($option) ? route('admin.variant-options.update', $option->id) : route('admin.variant-options.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($option))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header border-bottom-0">
                        <h4 class="card-title">{{ isset($option) ? 'Edit' : 'Add' }} Variant Option</h4>
                        <p class="text-muted">Add options like Silver, Gold for Color variant or Small, Medium for Size variant</p>
                    </div>
                    <div class="card-body">
                        <!-- Variant Type -->
                        <div class="form-group">
                            <label for="product_variant_id">Variant Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('product_variant_id') is-invalid @enderror" 
                                    id="product_variant_id" name="product_variant_id" required>
                                <option value="">Select Variant Type</option>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->id }}" 
                                        {{ old('product_variant_id', $option->product_variant_id ?? '') == $variant->id ? 'selected' : '' }}>
                                        {{ $variant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_variant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Select which variant type this option belongs to (e.g., Color, Size, Metal)</small>
                        </div>

                        <!-- Option Name -->
                        <div class="form-group">
                            <label for="option_name">Option Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('option_name') is-invalid @enderror" 
                                   id="option_name" name="option_name" 
                                   value="{{ old('option_name', $option->option_name ?? '') }}" 
                                   placeholder="e.g., Silver, Gold, Medium, Large" required>
                            @error('option_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SKU Code -->
                        <div class="form-group">
                            <label for="sku_code">SKU Code <span class="text-muted">(Auto-generated if empty)</span></label>
                            <input type="text" class="form-control @error('sku_code') is-invalid @enderror" 
                                   id="sku_code" name="sku_code" 
                                   value="{{ old('sku_code', $option->sku_code ?? '') }}" 
                                   placeholder="e.g., SIL, GLD, MD, LG">
                            @error('sku_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Short code used in combination SKUs (e.g., PROD001-SIL-MD)</small>
                        </div>

                        <!-- Additional Price -->
                        <div class="form-group">
                            <label for="additional_price">Additional Price ($)</label>
                            <input type="number" step="0.01" class="form-control @error('additional_price') is-invalid @enderror" 
                                   id="additional_price" name="additional_price" 
                                   value="{{ old('additional_price', $option->additional_price ?? '0') }}" 
                                   placeholder="0.00">
                            @error('additional_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Extra price added when this option is selected (e.g., Gold +$20)</small>
                        </div>

                        <!-- Display Order -->
                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" 
                                   id="display_order" name="display_order" 
                                   value="{{ old('display_order', $option->display_order ?? '0') }}">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Option Image -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Option Image (Optional)</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Upload image for this option (e.g., color swatch)</small>
                        </div>
                        <div id="image-preview" class="mt-3">
                            @if(isset($option) && $option->getRawOriginal('image'))
                                <img src="{{ $option->image }}" class="img-fluid rounded" alt="Option Image">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" 
                                   name="is_active" value="1" id="is_active"
                                   {{ old('is_active', $option->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="feather feather-save"></i> {{ isset($option) ? 'Update' : 'Create' }} Option
                        </button>
                        <a href="{{ route('admin.variant-options.index') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="feather feather-x"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('script')
    <script>
        // Image preview
        $('#image').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-fluid rounded" alt="Preview">');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
