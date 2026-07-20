<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resubmit Seller Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            {{-- Navigation Back to Dashboard --}}
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <a href="{{ route('seller.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                </a>
                <span class="badge bg-danger px-3 py-2 text-uppercase">Status: Rejection Correction</span>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white p-3">
                    <h4 class="mb-1"><i class="bi bi-pencil-square me-2"></i>Resubmit Registration Details</h4>
                    <p class="mb-0 small text-white-50">Correct your business information based on admin feedback and resubmit your application.</p>
                </div>

                <div class="card-body p-4">
                    {{-- Admin Rejection Reason Alert --}}
                    @if($seller->rejection_reason)
                        <div class="alert alert-danger border border-danger-subtle mb-4">
                            <div class="fw-bold text-uppercase small mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Admin Rejection Reason:</div>
                            <div class="fw-semibold text-dark p-2 bg-white rounded border border-danger-subtle">{{ $seller->rejection_reason }}</div>
                            <small class="text-muted d-block mt-2">Please make sure to correct the fields indicated above before submitting.</small>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.resubmit.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Business Name <span class="text-danger">*</span></label>
                                <input type="text" name="business_name" class="form-control" 
                                       value="{{ old('business_name', $seller->business_name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Owner Name <span class="text-danger">*</span></label>
                                <input type="text" name="owner_name" class="form-control" 
                                       value="{{ old('owner_name', $seller->owner_name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email Address (Read-only)</label>
                                <input type="email" class="form-control bg-light" value="{{ $seller->email }}" readonly disabled>
                                <small class="text-muted">Email address cannot be changed.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone Number (Read-only)</label>
                                <input type="text" class="form-control bg-light" value="{{ $seller->phone }}" readonly disabled>
                                <small class="text-muted">Phone number cannot be changed.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">GST Number</label>
                                <input type="text" name="gst_number" class="form-control" 
                                       value="{{ old('gst_number', $seller->gst_number) }}" placeholder="e.g. 22AAAAA0000A1Z5">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">PAN Number</label>
                                <input type="text" name="pan_number" class="form-control" 
                                       value="{{ old('pan_number', $seller->pan_number) }}" placeholder="e.g. ABCDE1234F">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Business Address <span class="text-danger">*</span></label>
                                <textarea name="business_address" class="form-control" rows="3" required>{{ old('business_address', $seller->business_address) }}</textarea>
                            </div>

                            {{-- Business Logo Upload --}}
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">Business Logo</label>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    @if($seller->business_logo)
                                        <div class="text-center">
                                            <img src="{{ asset('storage/'.$seller->business_logo) }}" alt="Current Logo" 
                                                 style="height: 70px; width: 70px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6;">
                                            <small class="d-block text-muted">Current Logo</small>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <input type="file" name="business_logo" id="business_logo" class="form-control" 
                                               accept=".jpg,.jpeg,.png,.webp" onchange="previewLogo(event)">
                                        <small class="text-muted d-block mt-1">Leave empty if you don't want to change your logo. Max 2MB (JPG, PNG, WEBP).</small>
                                    </div>
                                </div>

                                <div id="logo-preview-wrap" class="mt-2" style="display:none;">
                                    <img id="logo-preview" src="#" alt="New Logo Preview"
                                         style="height:70px;width:70px;object-fit:cover;border-radius:8px;border:2px solid #28a745;">
                                    <small class="text-success ms-2 fw-semibold">New Logo Preview</small>
                                </div>
                            </div>

                            <div class="col-12"><hr class="my-3"></div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" 
                                       value="{{ old('bank_name', $seller->bank_name) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Bank Account Number</label>
                                <input type="text" name="bank_account_number" class="form-control" 
                                       value="{{ old('bank_account_number', $seller->bank_account_number) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control" 
                                       value="{{ old('ifsc_code', $seller->ifsc_code) }}">
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-danger btn-lg px-4 fw-bold">
                                <i class="bi bi-send-check me-2"></i>Submit Updated Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function previewLogo(event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
                document.getElementById('logo-preview-wrap').style.display = 'flex';
                document.getElementById('logo-preview-wrap').style.alignItems = 'center';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
</body>
</html>
