<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Become a Seller</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h4>Register as a Seller</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('seller.register.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Business Name</label>
                                <input type="text" name="business_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Owner Name</label>
                                <input type="text" name="owner_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>GST Number</label>
                                <input type="text" name="gst_number" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>PAN Number</label>
                                <input type="text" name="pan_number" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Business Address</label>
                                <textarea name="business_address" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Bank Name</label>
                                <input type="text" name="bank_name" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Bank Account Number</label>
                                <input type="text" name="bank_account_number" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Submit Application</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="{{ route('seller.login') }}" class="text-decoration-none">Already a seller? Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
