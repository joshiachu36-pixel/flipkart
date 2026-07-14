@extends('layout.seller')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h4>My Products</h4>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Add New Product</a>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Approval Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="Product Image" width="50" height="50" class="rounded object-fit-cover">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $product->approval_status == 'Approved' ? 'success' : ($product->approval_status == 'Pending' ? 'warning text-dark' : 'danger') }}">
                                    {{ $product->approval_status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('seller.products.variants.manage', $product) }}" class="btn btn-sm btn-secondary"><i class="bi bi-layers"></i> Variants</a>

                                {{-- Delete Button — triggers confirmation modal --}}
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">You have not uploaded any products yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        </div>
    </div>
</div>

{{-- ===== Delete Confirmation Modal ===== --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Product?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete
                    <strong id="deleteProductName"></strong>?
                </p>
                <p class="text-muted small mb-0">
                    This will also remove all variants, variant images, wishlist entries, and cart records associated with this product. <strong>This action cannot be undone.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Cancel
                </button>
                {{-- The form action is updated dynamically via JS --}}
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Populate modal with the correct product name and form action when the button is clicked
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button      = event.relatedTarget;
            const productId   = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');

            document.getElementById('deleteProductName').textContent = productName;
            document.getElementById('deleteForm').action =
                '{{ url("seller/products") }}/' + productId;
        });
    });
</script>
@endsection
