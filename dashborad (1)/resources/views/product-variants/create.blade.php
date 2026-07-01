@extends('layouts.app')
@section('title') Create New Variant | Molikule Enterprise @endsection

@section('css')
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .premium-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Stepper UI */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .stepper-wrapper::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            height: 2px;
            width: 100%;
            background: #e9ecef;
            z-index: 1;
            transform: translateY(-50%);
        }

        .stepper-item {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #fff;
            padding: 0 10px;
        }

        .step-counter {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #74788d;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .stepper-item.active .step-counter {
            background: #556ee6;
            color: #fff;
            border-color: #556ee6;
        }

        .stepper-item.completed .step-counter {
            background: #34c38f;
            color: #fff;
            border-color: #34c38f;
        }

        .step-name {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 8px;
            color: #74788d;
        }

        .stepper-item.active .step-name {
            color: #556ee6;
        }

        .stepper-item.completed .step-name {
            color: #34c38f;
        }

        .wizard-step {
            display: none;
        }

        .wizard-step.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bg-soft-info {
            background-color: rgba(80, 165, 241, 0.1);
        }

        .bg-soft-success {
            background-color: rgba(52, 195, 143, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">
                    <i class="bx bx-plus-circle me-2 text-primary"></i>Create New Variant
                </h4>
            </div>
        </div>
    </div>

    <form id="create-variant-form" class="needs-validation" novalidate method="POST"
        action="{{ route('product-variants.store') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="variant_type" value="flavour_volume">

        <div class="row">
            {{-- Wizard Stepper --}}
            <div class="col-lg-10 offset-lg-1 mb-4">
                <div class="card premium-card">
                    <div class="card-body p-4">
                        <div class="stepper-wrapper">
                            <div class="stepper-item active" id="step-1-indicator">
                                <div class="step-counter">1</div>
                                <div class="step-name">Product Mapping</div>
                            </div>
                            <div class="stepper-item" id="step-2-indicator">
                                <div class="step-counter">2</div>
                                <div class="step-name">Characteristics</div>
                            </div>
                            <div class="stepper-item" id="step-3-indicator">
                                <div class="step-counter">3</div>
                                <div class="step-name">Inventory & Launch</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Column --}}
            <div class="col-lg-10 offset-lg-1">

                {{-- Step 1: Product Context --}}
                <div class="wizard-step active" id="step-1">
                    <div class="card premium-card mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="avatar-sm me-3" style="width:3rem; height:3rem;">
                                    <span class="avatar-title rounded-circle bg-soft-info text-info fs-3">
                                        <i class="bx bx-package"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Core Product Mapping</h5>
                                    <small class="text-muted">Define the parent product hierarchy</small>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label">Brand <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="brand_id" required>
                                        <option value="">Choose Brand</option>
                                        @foreach($brands ?? [] as $brand)
                                            <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="category_id" required>
                                        <option value="">Choose Category</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-primary">Select Product <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2 @error('product_id') is-invalid @enderror"
                                        id="product_id" name="product_id" required>
                                        <option value="">Choose Product...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary btn-lg px-5" onclick="nextStep(2)">Continue to
                                    Specs <i class="bx bx-right-arrow-alt ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Identity --}}
                <div class="wizard-step" id="step-2">
                    <div class="card premium-card mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="avatar-sm me-3" style="width:3rem; height:3rem;">
                                    <span class="avatar-title rounded-circle bg-soft-success text-success fs-3">
                                        <i class="bx bx-slider-alt"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Variant Characteristics</h5>
                                    <small class="text-muted">Specific flavour and volume details</small>
                                </div>
                            </div>

                            <div class="row g-4 align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Flavour / Aroma Profile <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bx bx-droplet"></i></span>
                                        <input type="text" class="form-control form-control-lg border-start-0"
                                            id="flavour_input" name="value" list="flavourList" placeholder="e.g. Lemon"
                                            required oninput="buildVariantName()">
                                    </div>
                                    <datalist id="flavourList">
                                        @if(isset($existingNames))
                                            @foreach($existingNames as $name)
                                                <option value="{{ trim(explode('–', $name)[0]) }}">
                                            @endforeach
                                        @endif
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Pack Size / Volume <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg select2" id="variant_unit" name="variant_unit"
                                        required onchange="buildVariantName()">
                                        <option value="">Select Size</option>
                                        @foreach(['50ml', '100ml', '200ml', '250ml', '500ml', '1L', '2L', '5L'] as $size)
                                            <option value="{{ $size }}">{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="bg-light p-3 rounded-3 border-dashed" style="border: 2px dashed #dbdde0;">
                                        <label class="form-label text-primary fw-bold small text-uppercase mb-1">Generated
                                            Display Title</label>
                                        <div class="d-flex align-items-center">
                                            <input type="text"
                                                class="form-control border-0 bg-transparent fw-bold fs-4 p-0 shadow-none"
                                                id="variant_name" name="variant_name" placeholder="Wait for input..."
                                                readonly>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary ms-3 px-3 rounded-pill"
                                                onclick="toggleNameEdit()">
                                                <i class="bx bx-pencil me-1"></i> Override
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-light btn-lg px-4" onclick="prevStep(1)"><i
                                        class="bx bx-left-arrow-alt me-1"></i> Back</button>
                                <button type="button" class="btn btn-primary btn-lg px-5" onclick="nextStep(3)">Inventory &
                                    Media <i class="bx bx-right-arrow-alt ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Inventory & Assets --}}
                <div class="wizard-step" id="step-3">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card premium-card mb-4 h-100">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-primary mb-4 d-flex align-items-center">
                                        <i class="bx bx-money-withdraw me-2"></i> Inventory & Supply
                                    </h5>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Market Retail Price (MRP) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white">₹</span>
                                                <input type="number" step="0.01" class="form-control" name="mrp_price"
                                                    required placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Discount Type</label>
                                            <select class="form-select" name="discount_type">
                                                <option value="">No Discount</option>
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="flat">Flat Amount (₹)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Discount Value</label>
                                            <input type="number" step="0.01" class="form-control" name="discount_value"
                                                placeholder="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Initial Stock <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="stock_quantity"
                                                    id="stock_quantity" required value="0">
                                                <span class="input-group-text"><i class="bx bx-package"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Safety Threshold (Alert) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="low_stock" value="5" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Internal Variant SKU <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="sku" placeholder="e.g. MK-V-001"
                                                required>
                                        </div>

                                        <hr class="my-3">
                                        <div class="col-md-12">
                                            <div class="form-check form-switch form-switch-lg">
                                                <input class="form-check-input" type="checkbox" id="active" name="active"
                                                    value="1" checked>
                                                <label class="form-check-label ms-2 fw-bold" for="active">Launch Variant for
                                                    Sale Immediately</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="card premium-card mb-4 h-100">
                                <div class="card-body p-4 text-center">
                                    <h6 class="card-title text-start mb-4">Variant Asset <span class="text-danger">*</span>
                                        <small class="text-muted">(1080x1080)</small></h6>
                                    <div class="mb-4">
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('assets/images/product/img-1.png') }}"
                                                class="img-thumbnail shadow-sm rounded-4" id="main-image-preview"
                                                style="width: 220px; height: 220px; object-fit: cover;">
                                            <label for="variant_image"
                                                class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle p-2 shadow"
                                                style="transform: translate(25%, 25%)">
                                                <i class="bx bx-camera fs-5"></i>
                                            </label>
                                            <input type="file" id="variant_image" name="variant_image" class="d-none"
                                                accept="image/*" onchange="previewFile(event)" required>
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0">Upload a unique image for this specific variant.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-light btn-lg px-4" onclick="prevStep(2)"><i
                                class="bx bx-left-arrow-alt me-1"></i> Back</button>
                        <button type="submit" class="btn btn-success btn-lg px-5 fw-bold" id="submit-btn">Deploy Now <i
                                class="bx bx-rocket ms-1"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });

            $('#brand_id').on('change', function () {
                $.get("{{ route('product-variants.get-categories') }}", { brand_id: $(this).val() }, function (data) {
                    let options = '<option value="">Select Category</option>';
                    data.forEach(c => options += `<option value="${c.category_id}">${c.category_name}</option>`);
                    $('#category_id').html(options).trigger('change');
                });
            });

            $('#category_id').on('change', function () {
                $.get("{{ route('product-variants.get-products') }}", { category_id: $(this).val(), brand_id: $('#brand_id').val() }, function (data) {
                    let options = '<option value="">Select Product</option>';
                    data.forEach(p => options += `<option value="${p.product_id}">${p.name}</option>`);
                    $('#product_id').html(options).trigger('change');
                });
            });

            $('#create-variant-form').on('submit', function (e) {
                e.preventDefault();
                const btn = document.getElementById('submit-btn');
                btn.disabled = true;
                btn.innerHTML = 'Deploying...';

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                })
                    .then(async res => {
                        const data = await res.json();
                        if (res.ok && data.success) {
                            Swal.fire({ title: 'Deployed!', text: 'New variant created.', icon: 'success', timer: 1000, showConfirmButton: false })
                                .then(() => window.location.href = "{{ route('product-variants.index') }}");
                        } else {
                            let errorHtml = '<ul class="text-start mt-2">';
                            if (data.errors) {
                                Object.values(data.errors).forEach(errArray => {
                                    errArray.forEach(err => {
                                        errorHtml += `<li class="text-danger small">${err}</li>`;
                                    });
                                });
                            } else {
                                errorHtml += `<li>${data.message || 'Something went wrong during deployment'}</li>`;
                            }
                            errorHtml += '</ul>';

                            Swal.fire({
                                title: 'Submission Failed',
                                html: errorHtml,
                                icon: 'error',
                                confirmButtonText: 'Review Fields'
                            });
                            btn.disabled = false;
                            btn.innerHTML = 'Deploy Now';
                        }
                    })
                    .catch(err => {
                        Swal.fire('Connection Error', 'Failed to reach the server. Please check your network.', 'error');
                        btn.disabled = false;
                        btn.innerHTML = 'Deploy Now';
                    });
            });
        });

        function nextStep(step) {
            const currentStep = step - 1;
            let isValid = true;
            $(`#step-${currentStep} [required]`).each(function () {
                if (!$(this).val()) {
                    if (!$(this).is('select')) {
                        $(this).addClass('is-invalid');
                    }
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire('Missing Information', 'Please fill all mandatory fields before proceeding.', 'warning');
                return;
            }

            $('.wizard-step').removeClass('active');
            $(`#step-${step}`).addClass('active');

            $('.stepper-item').removeClass('active');
            for (let i = 1; i <= step; i++) {
                $(`#step-${i}-indicator`).addClass('active');
                if (i < step) $(`#step-${i}-indicator`).addClass('completed');
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function prevStep(step) {
            $('.wizard-step').removeClass('active');
            $(`#step-${step}`).addClass('active');

            $('.stepper-item').removeClass('completed');
            $('.stepper-item').removeClass('active');
            for (let i = 1; i <= step; i++) {
                $(`#step-${i}-indicator`).addClass('active');
                if (i < step) $(`#step-${i}-indicator`).addClass('completed');
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }


        function previewFile(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = function () {
                        if (this.width !== 1080 || this.height !== 1080) {
                            Swal.fire({
                                title: 'Invalid Image Size',
                                html: `Your image is <b>${this.width}x${this.height}</b>. <br>The required size is exactly <b>1080x1080</b> pixels.`,
                                icon: 'error',
                                confirmButtonColor: '#f46a6a'
                            });
                            input.value = '';
                            document.getElementById('main-image-preview').src = '{{ asset('assets/images/product/img-1.png') }}';
                        } else {
                            document.getElementById('main-image-preview').src = e.target.result;
                            $(input).removeClass('is-invalid').addClass('is-valid');
                        }
                    };
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function buildVariantName() {
            const f = document.getElementById('flavour_input').value.trim();
            const v = document.getElementById('variant_unit').value;
            const n = document.getElementById('variant_name');
            if (n.hasAttribute('readonly')) {
                n.value = (f && v) ? (f + ' \u2013 ' + v) : (f || v || '');
            }
        }

        function toggleNameEdit() {
            const n = document.getElementById('variant_name');
            n.removeAttribute('readonly');
            n.classList.add('bg-white', 'border', 'px-2');
            n.focus();
        }
    </script>
@endpush