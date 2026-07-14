@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-md-12">
            <h4>Seller Management</h4>
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
                        <th>Business Name</th>
                        <th>Owner</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sellers as $seller)
                    <tr>
                        <td>{{ $seller->business_name }}</td>
                        <td>{{ $seller->owner_name }}</td>
                        <td>{{ $seller->email }}</td>
                        <td>{{ $seller->phone }}</td>
                        <td>
                            <span class="badge bg-{{ $seller->status == 'Approved' ? 'success' : ($seller->status == 'Pending' ? 'warning' : 'danger') }}">
                                {{ $seller->status }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.sellers.update', $seller) }}" method="POST" class="d-inline">
                                @csrf
                                <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                    <option value="Pending" {{ $seller->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ $seller->status == 'Approved' ? 'selected' : '' }}>Approve</option>
                                    <option value="Rejected" {{ $seller->status == 'Rejected' ? 'selected' : '' }}>Reject</option>
                                    <option value="Suspended" {{ $seller->status == 'Suspended' ? 'selected' : '' }}>Suspend</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $sellers->links() }}
        </div>
    </div>
</div>
@endsection
