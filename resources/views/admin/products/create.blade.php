@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4 col-md-9 col-lg-10 main-content">
    <div class="row mb-3">
        <div class="col">
            <h2>Add New Product</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">URL Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Will be auto-generated if empty">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Product Images <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*" >
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">First image will be used as thumbnail</small>
                            <div class="mt-2 d-flex flex-wrap" id="image-previews">
                                <img src="https://via.placeholder.com/300x300.png?text=No+Image" class="img-thumbnail" style="max-height: 100px;">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Product Thumbnail <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="thumbnail"  >
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">First image will be used as thumbnail</small>
                            <div class="mt-2 d-flex flex-wrap" id="image-previews">
                                <img src="https://via.placeholder.com/300x300.png?text=No+Image" class="img-thumbnail" style="max-height: 100px;">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Brand <span class="text-danger">*</span></label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="price" class="form-label">Regular Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="sale_price" class="form-label">Sale Price ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="cost" class="form-label">Cost ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{ old('cost') }}" required>
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="in_stock" {{ old('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                <option value="discontinued" {{ old('status') == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 form-check pt-4">
                            <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="featured">Featured Product</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-outline-secondary me-md-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
    }
    .attribute-row:not(:first-child) {
        margin-top: 10px;
    }
    #image-previews img {
        max-height: 100px;
        margin-right: 10px;
        margin-bottom: 10px;
    }
</style>



<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>


    // Auto-generate slug from name
    document.getElementById('name').addEventListener('blur', function() {
        if (!document.getElementById('slug').value) {
            fetch('/admin/generate-slug?text=' + encodeURIComponent(this.value))
                .then(response => response.json())
                .then(data => {
                    document.getElementById('slug').value = data.slug;
                });
        }
    });

    // Dynamic attribute fields
    document.getElementById('add-attribute').addEventListener('click', function() {
        const container = document.getElementById('attributes-container');
        const index = container.querySelectorAll('.attribute-row').length;
        const div = document.createElement('div');
        div.className = 'row mb-3 attribute-row';
        div.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="attributes[${index}][name]" class="form-control" placeholder="Specification (e.g., CPU)" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="attributes[${index}][value]" class="form-control" placeholder="Value (e.g., Intel i9)" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-attribute">Remove</button>
            </div>
        `;
        container.appendChild(div);
    });

    // Remove attribute row
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-attribute')) {
            const row = e.target.closest('.attribute-row');
            if (document.querySelectorAll('.attribute-row').length > 1) {
                row.remove();
            } else {
                row.querySelectorAll('input').forEach(input => input.value = '');
            }
        }
    });

    // Image previews
    document.getElementById('images').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('image-previews');
        previewContainer.innerHTML = '';

        Array.from(e.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'img-thumbnail me-2 mb-2';
                img.style.maxHeight = '100px';
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
</script>

