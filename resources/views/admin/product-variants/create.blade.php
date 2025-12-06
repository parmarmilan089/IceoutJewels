@extends('admin.layout.master')

@section('title', isset($variant) ? 'Edit Variant Type' : 'Add Variant Type')

@section('body')
    <div class="page-header d-lg-flex d-block">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1"><a href="{{ route('admin.product-variants.index') }}" class="text-decoration-none">Product Variants</a></li>
            <li class="breadcrumb-item1 active">{{ isset($variant) ? 'Edit' : 'Add' }} Variant Type</li>
        </ol>
    </div>

    <form action="{{ isset($variant) ? route('admin.product-variants.update', $variant->id) : route('admin.product-variants.store') }}" 
          method="POST">
        @csrf
        @if(isset($variant))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header border-bottom-0">
                        <h4 class="card-title">{{ isset($variant) ? 'Edit' : 'Add' }} Variant Type</h4>
                        <p class="text-muted">Create variant types like Color, Size, Metal, Diamond Quality, etc.</p>
                    </div>
                    <div class="card-body">
                        <!-- Variant Name -->
                        <div class="form-group">
                            <label for="name">Variant Type Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', $variant->name ?? '') }}" 
                                   placeholder="e.g., Color, Size, Metal, Diamond Quality" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">This is the type of variant (e.g., Color will have options like Silver, Gold)</small>
                        </div>

                        <!-- Display Order -->
                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" 
                                   id="display_order" name="display_order" 
                                   value="{{ old('display_order', $variant->display_order ?? '0') }}">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Order in which this variant appears (lower numbers appear first)</small>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       name="is_active" value="1" id="is_active"
                                       {{ old('is_active', $variant->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Information</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>What are Variant Types?</strong></p>
                        <p class="text-muted">Variant types are categories of product variations. For example:</p>
                        <ul class="text-muted">
                            <li><strong>Color:</strong> Silver, Gold, Rose Gold</li>
                            <li><strong>Size:</strong> Small, Medium, Large</li>
                            <li><strong>Metal:</strong> Titanium, Stainless Steel</li>
                            <li><strong>Diamond Quality:</strong> VVS, VS, SI</li>
                        </ul>
                        <p class="text-muted">After creating a variant type, you can add options for it in the "Variant Options" menu.</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="feather feather-save"></i> {{ isset($variant) ? 'Update' : 'Create' }} Variant Type
                        </button>
                        <a href="{{ route('admin.product-variants.index') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="feather feather-x"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
