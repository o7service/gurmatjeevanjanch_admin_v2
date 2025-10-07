@extends('layout.admin_layout')
@section('content')
    <div class="container">
        <div class="page-inner">
            <!-- Page Header -->
            <div class="page-header">
                <h3 class="fw-bold mb-3">Programs Links</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Programs Links</a></li>
                </ul>
            </div>

            <!-- Programs Links Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- Card Header -->
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                            <h5 class="card-title mb-0">Programs Links List</h5>
                            <!-- Add Button -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#programsLinkModal" onclick="prepareAddForm()">
                                <i class="bi bi-plus-lg"></i> ADD
                            </button>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <table class="table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($programsLinks as $t)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $t->title }}</td>
                                            <td>{{ $t->startDate }}</td>
                                            <td>{{ $t->endDate }}</td>
                                            <!-- Status Toggle -->
                                            <td>
                                                <form action="{{ route('programs.status', $t->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status"
                                                        value="{{ $t->isBlocked ? 'true' : 'false' }}">
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" onchange="this.form.submit()" {{ $t->isBlocked ? '' : 'checked' }}>
                                                        <span class="toggle-slider round"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td>{{ $t->created_at }}</td>
                                            <!-- Action Buttons -->
                                            <td>
                                                <button onclick="fetchData({{ $t->id }})"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $programsLinks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="programsLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="programsLinkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="programsForm" onsubmit="submitForm(); return false;" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="programsLinkModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="resetForm()"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" />
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title"
                                    required />
                            </div>

                            <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Enter address" required />
                            </div>

                            <div class="col-md-12">
                                <label for="details" class="form-label">Details</label>
                                <textarea class="form-control" id="details" name="details" placeholder="Enter details"
                                    rows="3" required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="mapLink" class="form-label">Map Link</label>
                                <input type="url" class="form-control" id="mapLink" name="mapLink"
                                    placeholder="Enter map URL" />
                            </div>

                            <div class="col-md-6">
                                <label for="imageUrl" class="form-label">Image</label>
                                <input type="file" class="form-control" id="imageUrl" name="imageUrl"
                                    accept=".png,.jpg,.jpeg" />
                            </div>

                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required />
                            </div>

                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required />
                            </div>

                            <div class="col-md-6">
                                <label for="contactNumber1" class="form-label">Contact Number 1</label>
                                <input type="text" class="form-control" id="contactNumber1" name="contactNumber1"
                                    placeholder="Enter contact number 1" required />
                            </div>

                            <div class="col-md-6">
                                <label for="contactNumber2" class="form-label">Contact Number 2</label>
                                <input type="text" class="form-control" id="contactNumber2" name="contactNumber2"
                                    placeholder="Enter contact number 2" />
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="formSubmitBtn" class="btn btn-primary" type="submit">Submit</button>
                        <button class="btn btn-danger" type="reset" onclick="resetForm()">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variable to track form type
        let formType = "add";

        /**
         * Prepare the modal for adding a new record
         */
        function prepareAddForm() {
            formType = "add";
            resetForm();
            document.getElementById('programsLinkModalLabel').innerText = "Add Programs Link";
            document.getElementById('formSubmitBtn').innerText = "Add";
        }

        /**
         * Fetch existing data and prepare the modal for editing
         */
        function fetchData(id) {
            showSpinner();
            formType = "edit";
            document.getElementById('programsLinkModalLabel').innerText = "Edit Programs Link";
            document.getElementById('formSubmitBtn').innerText = "Update";

            fetch(`/programs/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    document.getElementById("id").value = data.id;
                    document.getElementById("title").value = data.title;
                    document.getElementById("address").value = data.address;
                    document.getElementById("details").value = data.details;
                    document.getElementById("mapLink").value = data.mapLink;
                    document.getElementById("startDate").value = data.startDate;
                    document.getElementById("endDate").value = data.endDate;
                    document.getElementById("contactNumber1").value = data.contactNumber1;
                    document.getElementById("contactNumber2").value = data.contactNumber2;
                    hideSpinner();
                    const modal = new bootstrap.Modal(document.getElementById("programsLinkModal"));
                    modal.show();
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        /**
         * Submit form dynamically for Add or Edit
         */
        function submitForm() {
            const id = document.getElementById('id').value;
            const formData = new FormData(document.getElementById('programsForm'));
            let url = '';
            let method = '';
            if (formType === 'add') {
                url = '/programs';
                method = 'POST';
            } else {
                url = `/programs/${id}`;
                method = 'PUT';
            }

            if (formType === 'edit') formData.append('_method', 'PUT');

            showSpinner();
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })

                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    const modalEl = document.getElementById('programsLinkModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    setTimeout(() => {
                        location.reload();
                    }, 2100)
                    hideSpinner();
                    showToastr(data.message, "Success");
                })
                .catch(error => console.error('Error:', error));
        }

        /**
         * Reset form fields
         */

        function resetForm() {
            document.getElementById('programsForm').reset();
            document.getElementById('id').value = '';
        }
    </script>
    <!-- Status Toastr Messages -->
    @if (Session::has('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showToastr("{{ Session::get('success') }}", "Success");
            });
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showToastr("{{ Session::get('error') }}", "Error");
            });
        </script>
    @endif
@endsection