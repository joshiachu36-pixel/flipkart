@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-md-12">
            <h4>Pending Product Approvals</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Seller</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->seller->business_name ?? 'N/A' }}</td>
                        <td>${{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">{{ $product->approval_status }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" name="approval_status" value="Approved" class="btn btn-sm btn-success">Approve</button>
                                <button type="submit" name="approval_status" value="Rejected" class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No pending products.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
