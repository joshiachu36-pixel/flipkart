@extends('layout.seller')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Dashboard Overview</h2>
        </div>
    </div>

    {{-- ===== Product Approval Notifications ===== --}}
    @if(!empty($notifications))
        @foreach($notifications as $notif)
            @if($notif['type'] === 'approved')
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Product Approved!</strong> {{ $notif['message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @elseif($notif['type'] === 'rejected')
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i>
                    <strong>Product Rejected.</strong> {{ $notif['message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @else
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ $notif['message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach
    @endif

    {{-- ===== Stats Cards ===== --}}
    <div class="row">
        {{-- Total Products --}}
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box-seam"></i> Total Products</h5>
                    <h2 class="display-4">{{ $stats['total_products'] ?? 0 }}</h2>
                    <p class="card-text">Total products you have uploaded.</p>
                </div>
            </div>
        </div>

        {{-- Approved Products --}}
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-check-circle"></i> Approved</h5>
                    <h2 class="display-4">{{ $stats['approved_products'] ?? 0 }}</h2>
                    <p class="card-text">Products visible to customers.</p>
                </div>
            </div>
        </div>

        {{-- Pending Products --}}
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-dark h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-hourglass-split"></i> Pending</h5>
                    <h2 class="display-4">{{ $stats['pending_products'] ?? 0 }}</h2>
                    <p class="card-text">Awaiting admin approval.</p>
                </div>
            </div>
        </div>

        {{-- Rejected Products --}}
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-x-circle"></i> Rejected</h5>
                    <h2 class="display-4">{{ $stats['rejected_products'] ?? 0 }}</h2>
                    <p class="card-text">Products rejected by admin.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
