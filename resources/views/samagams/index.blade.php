@extends('layout.layout')

@section('content')
<div class="container">
    <div class="page-inner">

        <!-- ================== PAGE HEADER ================== -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Samagam Requests</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i></a>
                </li>
                <li class="separator"><i class="bi bi-arrow-right"></i></li>
                <li class="nav-item">Samagam Requests</li>
            </ul>
        </div>
        <!-- ================== END PAGE HEADER ================== -->


        <!-- ================== SAMAGAMS TABLE ================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <!-- Card Header -->
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="card-title mb-0">Samagams List</h5>

                        <!-- Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/samagam/all">All</a></li>
                                <li><a class="dropdown-item" href="/samagam/viewed">Viewed</a></li>
                                <li><a class="dropdown-item" href="/samagam/unviewed">Unviewed</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <table class="table table-head-bg-primary">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Organizer</th>
                                    <th>Contact</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($samagams as $t)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $t->organizerName }}</td>
                                    <td>{{ $t->phone }}</td>
                                    <td>{{ $t->startDate }}</td>
                                    <td>{{ $t->endDate }}</td>

                                    <!-- Action Buttons -->
                                    <td>
                                        <!-- View Modal Button -->
                                        <button 
                                            title="View" 
                                            class="btn btn-sm btn-primary view-samagam-btn me-2"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#samagamModal"
                                            data-organizer="{{ $t->organizerName }}" 
                                            data-address="{{ $t->address }}"
                                            data-phone="{{ $t->phone }}" 
                                            data-email="{{ $t->email }}"
                                            data-start="{{ $t->startDate }}" 
                                            data-end="{{ $t->endDate }}"
                                            data-details="{{ $t->details }}" 
                                            data-map="{{ $t->mapLink }}">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <!-- Mark as Viewed -->
                                        @if (!$t->isViewed)
                                        <form action="{{ route('samagam.markAsViewed', $t->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" title="Mark as Viewed">
                                                <i class="bi bi-check2-all"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        {{ $samagams->links() }}
                    </div>

                </div>
            </div>
        </div>
        <!-- ================== END TABLE ================== -->


        <!-- ================== SAMAGAM DETAILS MODAL ================== -->
        <div class="modal fade" id="samagamModal" tabindex="-1" aria-labelledby="samagamModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Samagam Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p><strong>Organizer:</strong> <span id="modalOrganizer"></span></p>
                        <p><strong>Address:</strong> <span id="modalAddress"></span></p>
                        <p><strong>Contact:</strong> <span id="modalPhone"></span></p>
                        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                        <p><strong>Start Date:</strong> <span id="modalStart"></span></p>
                        <p><strong>End Date:</strong> <span id="modalEnd"></span></p>
                        <p><strong>Details:</strong> <span id="modalDetails"></span></p>
                        <p><strong>Map Link:</strong> <a href="" target="_blank" id="modalMap">View Map</a></p>
                    </div>

                </div>
            </div>
        </div>
        <!-- ================== END MODAL ================== -->


        <!-- ================== JAVASCRIPT ================== -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.view-samagam-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        document.getElementById('modalOrganizer').textContent = this.dataset.organizer;
                        document.getElementById('modalAddress').textContent = this.dataset.address;
                        document.getElementById('modalPhone').textContent = this.dataset.phone;
                        document.getElementById('modalEmail').textContent = this.dataset.email;
                        document.getElementById('modalStart').textContent = this.dataset.start;
                        document.getElementById('modalEnd').textContent = this.dataset.end;
                        document.getElementById('modalDetails').textContent = this.dataset.details;
                        document.getElementById('modalMap').href = this.dataset.map;
                    });
                });
            });
        </script>

    </div>
</div>
@endsection
