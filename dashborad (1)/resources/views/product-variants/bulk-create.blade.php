@extends('layouts.app')
@section('css')
<link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    /* Premium Select2 Styling */
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        padding: 5px !important;
        border: 1px solid #ced4da !important;
        border-radius: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-dropdown {
        border-radius: 6px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
</style>
@endsection
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Bulk Create Variants</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('product-variants.index') }}">Product Variants</a></li>
                    <li class="breadcrumb-item active">Bulk Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<form id="bulk-variant-form" action="{{ route('product-variants.bulk-store') }}" method="POST">
    @csrf

    <!-- Step 1: Select Product -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bx bx-package me-2"></i>Step 1: Select Product
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                                <select class="form-control @error('product_id') is-invalid @enderror" 
                                        id="product_id" name="product_id" required>
                                    <option value="">Select Product</option>
                                    @foreach($products ?? [] as $product)
                                        <option value="{{ $product->product_id }}" 
                                                data-price="{{ $product->price }}"
                                                {{ old('product_id') == $product->product_id ? 'selected' : '' }}>
                                            {{ $product->name }} (₹{{ number_format($product->price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info mb-0">
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>Base Price:</strong> <span id="base-price-display">₹0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2: Select Attributes & Values -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bx bx-list-check me-2"></i>Step 2: Select Attributes & Values
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($attributes ?? [] as $attribute)
                    <div class="attribute-section mb-4 pb-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input attribute-toggle" type="checkbox" 
                                       id="attr_{{ $attribute->id }}" 
                                       name="attributes[]" 
                                       value="{{ $attribute->id }}"
                                       data-attribute-id="{{ $attribute->id }}">
                                <label class="form-check-label fw-bold" for="attr_{{ $attribute->id }}">
                                    {{ $attribute->name }}
                                    @if($attribute->is_required)
                                        <span class="badge bg-danger-subtle text-danger">Required</span>
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div class="attribute-values" id="values_{{ $attribute->id }}" style="display: none;">
                            <div class="row g-2">
                                @foreach($attribute->activeValues as $value)
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input attribute-value-checkbox" 
                                                   type="checkbox" 
                                                   name="attribute_values[{{ $attribute->id }}][]" 
                                                   value="{{ $value->id }}"
                                                   id="val_{{ $value->id }}"
                                                   data-attribute-id="{{ $attribute->id }}"
                                                   data-value="{{ $value->value }}"
                                                   data-short-code="{{ $value->short_code }}"
                                                   data-price-modifier="{{ $value->price_modifier }}"
                                                   data-price-modifier-type="{{ $value->price_modifier_type }}">
                                            <label class="form-check-label d-flex align-items-center" for="val_{{ $value->id }}">
                                                @if($value->hasColorCode())
                                                    <span class="color-swatch me-2" 
                                                          style="background-color: {{ $value->color_code }}; width: 20px; height: 20px; border-radius: 3px; border: 1px solid #ddd; display: inline-block;"></span>
                                                @endif
                                                {{ $value->display_name }}
                                                @if($value->formatted_price_modifier)
                                                    <span class="badge bg-info-subtle text-info ms-2">{{ $value->formatted_price_modifier }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3: Preview Combinations -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-grid-alt me-2"></i>Step 3: Preview Combinations
                        <span class="badge bg-dark ms-2" id="combination-count">0 variants</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="combinations-preview" class="alert alert-secondary">
                        <i class="bx bx-info-circle me-2"></i>Select attributes and values to see combinations
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 4: Set Default Values -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bx bx-cog me-2"></i>Step 4: Set Default Values
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="default_stock" class="form-label">Default Stock Quantity</label>
                                <input type="number" class="form-control" id="default_stock" 
                                       name="default_stock" value="100" min="0">
                                <small class="text-muted">Applied to all variants</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="default_active" 
                                           name="default_active" value="1" checked>
                                    <label class="form-check-label" for="default_active">
                                        Set all variants as Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 5: Generate -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('product-variants.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success btn-lg" id="generate-btn" disabled>
                            <i class="bx bx-check-circle me-1"></i>Generate <span id="generate-count">0</span> Variants
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Premium Select2 Init
    $('#product_id').select2({
        placeholder: 'Search & Select Product',
        allowClear: true,
        width: '100%'
    });

    const productSelect = document.getElementById('product_id');
    const basePriceDisplay = document.getElementById('base-price-display');
    const combinationsPreview = document.getElementById('combinations-preview');
    const combinationCount = document.getElementById('combination-count');
    const generateBtn = document.getElementById('generate-btn');
    const generateCount = document.getElementById('generate-count');

    let basePrice = 0;
    let selectedAttributes = {};

    // Update base price when product changes
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        basePrice = parseFloat(selectedOption.dataset.price) || 0;
        basePriceDisplay.textContent = '₹' + basePrice.toFixed(2);
        updateCombinations();
    });

    // Toggle attribute values visibility
    document.querySelectorAll('.attribute-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const attributeId = this.dataset.attributeId;
            const valuesDiv = document.getElementById('values_' + attributeId);
            
            if (this.checked) {
                valuesDiv.style.display = 'block';
            } else {
                valuesDiv.style.display = 'none';
                // Uncheck all values
                valuesDiv.querySelectorAll('.attribute-value-checkbox').forEach(cb => {
                    cb.checked = false;
                });
            }
            
            updateCombinations();
        });
    });

    // Update combinations when values change
    document.querySelectorAll('.attribute-value-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateCombinations);
    });

    function updateCombinations() {
        selectedAttributes = {};
        
        // Collect selected values for each attribute
        document.querySelectorAll('.attribute-toggle:checked').forEach(attrToggle => {
            const attributeId = attrToggle.dataset.attributeId;
            const values = [];
            
            document.querySelectorAll(`.attribute-value-checkbox[data-attribute-id="${attributeId}"]:checked`).forEach(valueCheckbox => {
                values.push({
                    id: valueCheckbox.value,
                    value: valueCheckbox.dataset.value,
                    shortCode: valueCheckbox.dataset.shortCode,
                    priceModifier: parseFloat(valueCheckbox.dataset.priceModifier) || 0,
                    priceModifierType: valueCheckbox.dataset.priceModifierType
                });
            });
            
            if (values.length > 0) {
                selectedAttributes[attributeId] = values;
            }
        });

        // Generate combinations
        const combinations = generateCombinations(selectedAttributes);
        displayCombinations(combinations);
    }

    function generateCombinations(attributes) {
        const attributeKeys = Object.keys(attributes);
        
        if (attributeKeys.length === 0) {
            return [];
        }

        let combinations = [{}];

        attributeKeys.forEach(attrId => {
            const newCombinations = [];
            attributes[attrId].forEach(value => {
                combinations.forEach(combo => {
                    newCombinations.push({
                        ...combo,
                        [attrId]: value
                    });
                });
            });
            combinations = newCombinations;
        });

        return combinations;
    }

    function displayCombinations(combinations) {
        const count = combinations.length;
        combinationCount.textContent = count + ' variant' + (count !== 1 ? 's' : '');
        generateCount.textContent = count;
        
        if (count === 0) {
            combinationsPreview.innerHTML = '<i class="bx bx-info-circle me-2"></i>Select attributes and values to see combinations';
            combinationsPreview.className = 'alert alert-secondary';
            generateBtn.disabled = true;
            return;
        }

        generateBtn.disabled = false;

        let html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr>';
        html += '<th>#</th>';
        
        // Header
        const firstCombo = combinations[0];
        Object.keys(firstCombo).forEach(attrId => {
            const attrToggle = document.querySelector(`.attribute-toggle[data-attribute-id="${attrId}"]`);
            html += '<th>' + (attrToggle ? attrToggle.nextElementSibling.textContent.trim() : 'Attribute') + '</th>';
        });
        html += '<th>Calculated Price</th></tr></thead><tbody>';

        // Rows
        combinations.forEach((combo, index) => {
            html += '<tr><td>' + (index + 1) + '</td>';
            
            let totalPrice = basePrice;
            Object.values(combo).forEach(value => {
                html += '<td>' + value.value + '</td>';
                
                // Calculate price
                if (value.priceModifierType === 'percentage') {
                    totalPrice += (basePrice * (value.priceModifier / 100));
                } else {
                    totalPrice += value.priceModifier;
                }
            });
            
            html += '<td class="fw-bold text-success">₹' + totalPrice.toFixed(2) + '</td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        
        combinationsPreview.innerHTML = html;
        combinationsPreview.className = 'alert alert-success';
    }
});
</script>
@endpush

