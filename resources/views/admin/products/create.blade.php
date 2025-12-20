@extends('admin.layout.master')

@section('title', isset($product) ? 'Edit Product' : 'Add New Product')

@section('body')
    <!-- Page header -->
    <div class="page-header d-lg-flex d-block">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1"><a href="{{ route('admin.products.index') }}" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item1 active">{{ isset($product) ? 'Edit' : 'Add' }} Product</li>
        </ol>
    </div>
    <!-- End Page header -->

    <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header border-bottom-0">
                        <h4 class="card-title">{{ isset($product) ? 'Edit' : 'Add' }} Product Information</h4>
                    </div>
                    <div class="card-body">
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', $product->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div class="form-group">
                            <label for="sku">SKU <span class="text-muted">(Auto-generated if empty)</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" name="sku" 
                                   value="{{ old('sku', $product->sku ?? $sku ?? '') }}" 
                                   placeholder="Leave empty for auto-generation">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category & Sub Category -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_category_id">Sub Category</label>
                                    <select class="form-control @error('sub_category_id') is-invalid @enderror" 
                                            id="sub_category_id" name="sub_category_id">
                                        <option value="">Select Sub Category</option>
                                    </select>
                                    @error('sub_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div class="form-group">
                            <label for="short_description">Short Description</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                      id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Long Description -->
                        <div class="form-group">
                            <label for="long_description">Long Description</label>
                            <textarea class="form-control @error('long_description') is-invalid @enderror" 
                                      id="long_description" name="long_description" rows="5">{{ old('long_description', $product->long_description ?? '') }}</textarea>
                            @error('long_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender, Material, Weight -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="men" {{ old('gender', $product->gender ?? '') == 'men' ? 'selected' : '' }}>Men</option>
                                        <option value="women" {{ old('gender', $product->gender ?? '') == 'women' ? 'selected' : '' }}>Women</option>
                                        <option value="unisex" {{ old('gender', $product->gender ?? '') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="material">Material <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                           id="material" name="material" 
                                           value="{{ old('material', $product->material ?? '') }}" 
                                           placeholder="e.g., 925 Sterling Silver" required>
                                    @error('material')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="weight">Weight (grams) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" 
                                           id="weight" name="weight" 
                                           value="{{ old('weight', $product->weight ?? '') }}" required>
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Base Price -->
                        <div class="form-group">
                            <label for="base_price">Base Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror" 
                                   id="base_price" name="base_price" 
                                   value="{{ old('base_price', $product->base_price ?? '') }}" required>
                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duty & Shipping Fees -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="duty_fee">Duty Fee ($)</label>
                                    <input type="number" step="0.01" class="form-control @error('duty_fee') is-invalid @enderror" 
                                           id="duty_fee" name="duty_fee" 
                                           value="{{ old('duty_fee', $product->duty_fee ?? '0') }}">
                                    @error('duty_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="customs_fee">Customs Fee ($)</label>
                                    <input type="number" step="0.01" class="form-control @error('customs_fee') is-invalid @enderror" 
                                           id="customs_fee" name="customs_fee" 
                                           value="{{ old('customs_fee', $product->customs_fee ?? '0') }}">
                                    @error('customs_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="insurance_fee">Insurance Fee ($)</label>
                                    <input type="number" step="0.01" class="form-control @error('insurance_fee') is-invalid @enderror" 
                                           id="insurance_fee" name="insurance_fee" 
                                           value="{{ old('insurance_fee', $product->insurance_fee ?? '0') }}">
                                    @error('insurance_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="shipping_fee">Shipping Fee ($)</label>
                                    <input type="number" step="0.01" class="form-control @error('shipping_fee') is-invalid @enderror" 
                                           id="shipping_fee" name="shipping_fee" 
                                           value="{{ old('shipping_fee', $product->shipping_fee ?? '0') }}">
                                    @error('shipping_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SEO Fields -->
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                   id="meta_title" name="meta_title" 
                                   value="{{ old('meta_title', $product->meta_title ?? '') }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                      id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Video URL -->
                        <div class="form-group">
                            <label for="video_url">Video URL</label>
                            <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                   id="video_url" name="video_url" 
                                   value="{{ old('video_url', $product->video_url ?? '') }}" 
                                   placeholder="https://youtube.com/...">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Product Image -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Featured Image</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                   id="featured_image" name="featured_image" accept="image/*">
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="image-preview" class="mt-3">
                            @if(isset($product) && $product->getRawOriginal('featured_image'))
                                <img src="{{ $product->featured_image }}" class="img-fluid rounded" alt="Product Image">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Variant Types -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Product Variants</h4>
                    </div>
                    <div class="card-body">
                    <div class="card-body">
                        @foreach($variants as $variant)
                            <div class="variant-item border-bottom pb-2 mb-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input variant-checkbox" type="checkbox" 
                                           name="variant_types[]" value="{{ $variant->id }}" 
                                           id="variant_{{ $variant->id }}"
                                           data-id="{{ $variant->id }}"
                                           {{ isset($product) && $product->variants->contains($variant->id) ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold" for="variant_{{ $variant->id }}">
                                        {{ $variant->name }}
                                    </label>
                                </div>
                                
                                <div class="variant-options-wrapper ml-4" id="variant_options_{{ $variant->id }}" 
                                     style="display: {{ isset($product) && $product->variants->contains($variant->id) ? 'block' : 'none' }};">
                                    <table class="table table-sm table-borderless mb-2">
                                        <tbody id="options_tbody_{{ $variant->id }}">
                                            @if(isset($selectedOptions) && isset($selectedOptions[$variant->id]))
                                                @foreach($selectedOptions[$variant->id] as $selectedOption)
                                                    <tr>
                                                        <td class="pl-0">
                                                            <select class="form-control form-control-sm variant-option-select" 
                                                                    name="variant_options[{{ $variant->id }}][names][]" 
                                                                    data-variant-id="{{ $variant->id }}" required>
                                                                <option value="">Select Option</option>
                                                                @foreach($variant->options as $availableOption)
                                                                    <option value="{{ $availableOption->option_name }}"
                                                                        {{ $availableOption->option_name == $selectedOption->option_name ? 'selected' : '' }}>
                                                                        {{ $availableOption->option_name }} (Default: ${{ number_format($availableOption->additional_price, 2) }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">$</span>
                                                                <input type="number" step="0.01" class="form-control form-control-sm option-price-input" 
                                                                       name="variant_options[{{ $variant->id }}][prices][]" 
                                                                       value="{{ $selectedOption->additional_price }}" 
                                                                       placeholder="Price">
                                                            </div>
                                                        </td>
                                                        <td class="text-right pr-0" style="width: 40px;">
                                                            <button type="button" class="btn btn-sm btn-danger remove-option-btn">
                                                                <i class="feather feather-x"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-xs btn-outline-primary add-option-btn" data-variant-id="{{ $variant->id }}">
                                        <i class="feather feather-plus"></i> Add {{ $variant->name }} Option
                                    </button>
                                </div>
                            </div>
                        @endforeach
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
                                   name="status" value="1" id="status"
                                   {{ old('status', $product->status ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" 
                                   name="is_featured" value="1" id="is_featured"
                                   {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="feather feather-save"></i> {{ isset($product) ? 'Update' : 'Create' }} Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100 mt-2">
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
        // Variant Data from Controller
        window.variantOptionsData = {
            @foreach($variants as $variant)
                {{ $variant->id }}: [
                    @foreach($variant->options as $opt)
                        {
                            id: "{{ $opt->id }}",
                            name: "{{ addslashes($opt->option_name) }}",
                            price: "{{ $opt->additional_price }}"
                        },
                    @endforeach
                ],
            @endforeach
        };

        // Image preview
        $('#featured_image').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-fluid rounded" alt="Preview">');
                }
                reader.readAsDataURL(file);
            }
        });

        // Variant Options Logic
        $(document).on('change', '.variant-checkbox', function() {
            var variantId = $(this).data('id');
            var container = $('#variant_options_' + variantId);
            
            if ($(this).is(':checked')) {
                container.slideDown();
                // Add one empty row if none exist
                if (container.find('tbody tr').length === 0) {
                    addOptionRow(variantId);
                }
            } else {
                container.slideUp();
            }
        });

        $(document).on('click', '.add-option-btn', function() {
            var variantId = $(this).data('variant-id');
            addOptionRow(variantId);
        });

        $(document).on('click', '.remove-option-btn', function() {
            $(this).closest('tr').remove();
        });
        
        // Auto-fill price on option selection
        $(document).on('change', '.variant-option-select', function() {
            var variantId = $(this).data('variant-id');
            var selectedName = $(this).val(); // We use Name as value to match backend logic
            var priceInput = $(this).closest('tr').find('.option-price-input');
            
            // Find option data
            var options = window.variantOptionsData[variantId] || [];
            var found = options.find(o => o.name == selectedName);
            
            if (found) {
                priceInput.val(found.price);
            }
        });

        function addOptionRow(variantId) {
            var options = window.variantOptionsData[variantId] || [];
            var optionsHtml = '<option value="">Select Option</option>';
            
            options.forEach(function(opt) {
                optionsHtml += `<option value="${opt.name}">${opt.name} (Default: $${opt.price})</option>`;
            });

            var row = `
                <tr>
                    <td class="pl-0">
                        <select class="form-control form-control-sm variant-option-select" 
                                name="variant_options[` + variantId + `][names][]" 
                                data-variant-id="${variantId}" required>
                            ${optionsHtml}
                        </select>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control form-control-sm option-price-input" 
                                   name="variant_options[` + variantId + `][prices][]" 
                                   value="0" placeholder="Price">
                        </div>
                    </td>
                    <td class="text-right pr-0" style="width: 40px;">
                        <button type="button" class="btn btn-sm btn-danger remove-option-btn">
                            <i class="feather feather-x"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#options_tbody_' + variantId).append(row);
        }
    </script>
@endsection
