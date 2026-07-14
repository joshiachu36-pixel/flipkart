@extends('layout.admin')

@section('content')
<div class="container-fluid pt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-bar-chart-fill me-2"></i> Marketplace Reports & Analytics</h2>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="small-box bg-info text-white shadow-sm h-100">
                <div class="inner">
                    <h3>{{ $totalSellers }}</h3>
                    <p>Total Sellers</p>
                </div>
                <div class="icon">
                    <i class="bi bi-shop"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="small-box bg-primary text-white shadow-sm h-100">
                <div class="inner">
                    <h3>{{ $totalProducts }}</h3>
                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="small-box bg-danger text-white shadow-sm h-100">
                <div class="inner">
                    <h3>{{ $totalWishlist }}</h3>
                    <p>Wishlist Adds</p>
                </div>
                <div class="icon">
                    <i class="bi bi-heart-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="small-box bg-success text-white shadow-sm h-100">
                <div class="inner">
                    <h3>{{ $totalCart }}</h3>
                    <p>Cart Adds</p>
                </div>
                <div class="icon">
                    <i class="bi bi-cart-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="small-box bg-warning text-dark shadow-sm h-100">
                <div class="inner">
                    <h5>{{ $topSellerData['seller']->business_name ?? 'N/A' }}</h5>
                    <p>Top Seller</p>
                </div>
                <div class="icon">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="small-box bg-secondary text-white shadow-sm h-100">
                <div class="inner">
                    <h5>{{ Str::limit($mostPopular->product->name ?? 'N/A', 15) }}</h5>
                    <p>Most Popular Product</p>
                </div>
                <div class="icon">
                    <i class="bi bi-star-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Top Sellers (Interest)</h3>
                </div>
                <div class="card-body">
                    <div id="sellerChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">Top Products (Wishlist & Cart)</h3>
                </div>
                <div class="card-body">
                    <div id="productChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row">
        <!-- Most Popular Products Table -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0">Most Popular Products</h3>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 400px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Product Name</th>
                                <th>Seller</th>
                                <th class="text-center">Wishlist Count</th>
                                <th class="text-center">Cart Count</th>
                                <th class="text-center">Total Interest</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularProducts as $analytics)
                                <tr>
                                    <td>{{ $analytics->product->name ?? 'Unknown' }}</td>
                                    <td>{{ $analytics->product->seller->business_name ?? 'Unknown' }}</td>
                                    <td class="text-center">{{ $analytics->wishlist_count }}</td>
                                    <td class="text-center">{{ $analytics->cart_count }}</td>
                                    <td class="text-center fw-bold">{{ $analytics->total_interest }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No product analytics data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Seller Performance Table -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">Seller Performance / Top Sellers</h3>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 400px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Rank</th>
                                <th>Seller</th>
                                <th class="text-center">Products</th>
                                <th class="text-center">Interest</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellerPerformance as $index => $data)
                                <tr>
                                    <td>#{{ $index + 1 }}</td>
                                    <td>{{ $data['seller']->business_name ?? 'Unknown' }}</td>
                                    <td class="text-center">{{ $data['product_count'] }}</td>
                                    <td class="text-center fw-bold">{{ $data['total_interest'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No seller analytics data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Category Performance Table -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title mb-0">Category Performance</h3>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 400px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Category Name</th>
                                <th class="text-center">Wishlist</th>
                                <th class="text-center">Cart</th>
                                <th class="text-center">Total Interest</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryPerformance as $data)
                                <tr>
                                    <td>{{ $data['category']->name ?? 'Unknown' }}</td>
                                    <td class="text-center">{{ $data['wishlist_count'] }}</td>
                                    <td class="text-center">{{ $data['cart_count'] }}</td>
                                    <td class="text-center fw-bold">{{ $data['total_interest'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No category analytics data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Top Sellers Chart
        var sellerOptions = {
            series: [{
                name: 'Wishlist Count',
                data: {!! json_encode($sellerChartWishlist) !!}
            }, {
                name: 'Cart Count',
                data: {!! json_encode($sellerChartCart) !!}
            }],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: { horizontal: true, borderRadius: 4 }
            },
            stroke: { width: 1, colors: ['#fff'] },
            xaxis: {
                categories: {!! json_encode($sellerChartLabels) !!},
            },
            fill: { opacity: 1 },
            colors: ['#dc3545', '#198754'],
            legend: { position: 'top', horizontalAlign: 'left' }
        };
        var sellerChart = new ApexCharts(document.querySelector("#sellerChart"), sellerOptions);
        sellerChart.render();

        // Top Products Chart
        var productOptions = {
            series: [{
                name: 'Wishlist Count',
                data: {!! json_encode($productChartWishlist) !!}
            }, {
                name: 'Cart Count',
                data: {!! json_encode($productChartCart) !!}
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 }
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: {!! json_encode($productChartLabels) !!},
            },
            yaxis: { title: { text: 'Count' } },
            fill: { opacity: 1 },
            colors: ['#dc3545', '#198754'],
            tooltip: { y: { formatter: function (val) { return val + " counts" } } }
        };
        var productChart = new ApexCharts(document.querySelector("#productChart"), productOptions);
        productChart.render();
    });
</script>
@endsection
