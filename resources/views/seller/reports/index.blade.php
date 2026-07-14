@extends('layout.seller')

@section('content')
<div class="container-fluid pt-3">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-bar-chart-line-fill me-2"></i> Seller Analytics & Reports</h2>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="bi bi-box"></i> Total Products</h5>
                    <h2 class="display-5">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="bi bi-heart-fill"></i> Wishlist Adds</h5>
                    <h2 class="display-5">{{ $totalWishlist }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="bi bi-cart-fill"></i> Cart Adds</h5>
                    <h2 class="display-5">{{ $totalCart }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-secondary text-white h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="bi bi-star-fill"></i> Top Product</h5>
                    <p class="mt-3 fs-5">{{ Str::limit($mostPopular->product->name ?? 'N/A', 20) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Top Products by Interest</h5>
                </div>
                <div class="card-body">
                    <div id="interestChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products and Zero Interest Row -->
    <div class="row">
        <!-- Top Products Table -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Top Products by Customer Interest</h5>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 400px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Wishlist Count</th>
                                <th class="text-center">Cart Count</th>
                                <th class="text-center">Total Interest</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $analytics)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if(optional($analytics->product)->image)
                                                <img src="{{ asset('storage/' . $analytics->product->image) }}" alt="{{ $analytics->product->name }}" class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            {{ $analytics->product->name ?? 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $analytics->wishlist_count }}</td>
                                    <td class="text-center">{{ $analytics->cart_count }}</td>
                                    <td class="text-center fw-bold">{{ $analytics->total_interest }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No product analytics data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Zero Interest Products -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">Zero Interest Products</h5>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 400px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Product</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($untracked as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                </tr>
                            @empty
                                @forelse($zeroInterest as $analytics)
                                    <tr>
                                        <td>{{ $analytics->product->name ?? 'Unknown' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted">All your products have some interest!</td>
                                    </tr>
                                @endforelse
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Variant Analytics Row (if applicable) -->
    @if($variantAnalytics->isNotEmpty())
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Variant Analytics (Colors & Sizes)</h5>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 400px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Product</th>
                                <th>Color</th>
                                <th>Sizes Available</th>
                                <th class="text-center">Wishlist Activity</th>
                                <th class="text-center">Cart Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($variantAnalytics as $va)
                                <tr>
                                    <td>{{ $va['product']->name ?? 'Unknown' }}</td>
                                    <td>{{ $va['color']->name ?? 'N/A' }}</td>
                                    <td>
                                        @foreach($va['sizes'] as $size)
                                            <span class="badge bg-secondary">{{ $size->name }} ({{ $size->pivot->stock }})</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $va['wishlist'] }}</td>
                                    <td class="text-center">{{ $va['cart'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add ApexCharts Script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            series: [{
                name: 'Wishlist Count',
                data: {!! json_encode($chartWishlist) !!}
            }, {
                name: 'Cart Count',
                data: {!! json_encode($chartCart) !!}
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: {!! json_encode($chartLabels) !!},
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Customer Actions'
                }
            },
            fill: {
                opacity: 1
            },
            colors: ['#dc3545', '#198754'],
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " counts"
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#interestChart"), options);
        chart.render();
    });
</script>
@endsection
