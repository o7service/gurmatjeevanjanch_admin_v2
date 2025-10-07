@extends('layout.admin_layout')
@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Page Header -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Categories</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Categories</a></li>
            </ul>
        </div>

        <!-- Categories Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- Card Header -->
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="card-title mb-0">Categories List</h5>
                        <!-- Add Button -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#categoryModal" onclick="prepareAddForm()">
                            <i class="bi bi-plus-lg"></i> ADD
                        </button>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <table class="table table-head-bg-primary">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $c)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $c->title }}</td>
                                    <td>
                                        <img src="{{ $c->icon ? url('categories/' . $c->icon) : asset('image.png') }}"
                                            style="height: 60px; width: 60px; border-radius: 50%;" alt="category">
                                    </td>

                                    <!-- Status Toggle -->
                                    <td>
                                        <form action="{{ route('category.status', $c->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status"
                                                value="{{ $c->isBlocked ? 'true' : 'false' }}">
                                            <label class="toggle-switch">
                                                <input type="checkbox" onchange="this.form.submit()" {{ $c->isBlocked ? '' : 'checked' }}>
                                                <span class="toggle-slider round"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>{{ $c->created_at->format('d M Y') }}</td>
                                    
                                    <!-- Action Buttons -->
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="fetchData({{ $c->id }})">
                                            Edit
                                        </button>
                                        <form action="{{ route('category.status', $c->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fs-5 fw-semibold" id="categoryModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="resetForm()"></button>
            </div>
            <div class="modal-body pt-1">
                <form id="categoryForm" onsubmit="submitForm(); return false;" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" />
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Category Name</label>
                        <input type="text" class="form-control form-control-sm py-1"
                            id="title" name="title" placeholder="Enter title"
                            required style="font-size: 0.8rem;" />
                    </div>
                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Image</label>
                        <input type="file" class="form-control form-control-sm py-1" style="font-size: 0.8rem;" 
                            id="file" name="file" accept=".jpg,.jpeg,.png" />
                    </div>
                    <!-- Modal Actions -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="reset" onclick="resetForm()" class="btn btn-sm btn-danger px-3">Reset</button>
                        <button type="submit" id="formSubmitBtn" class="btn btn-sm btn-primary px-3">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
        // Global variable to track form type
        let formType = "add";

        /**
         * Prepare the modal for adding a new record
         */
        function prepareAddForm() {
            formType = "add";
            resetForm();
            document.getElementById('categoryModalLabel').innerText = "Add Category";
            document.getElementById('formSubmitBtn').innerText = "Add";
        }

        /**
         * Fetch existing data and prepare the modal for editing
         */
        function fetchData(id) {
            showSpinner();
            formType = "edit";
            document.getElementById('categoryModalLabel').innerText = "Edit singer Link";
            document.getElementById('formSubmitBtn').innerText = "Update";

            fetch(`/category/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    document.getElementById("id").value = data.id;
                    document.getElementById("title").value = data.title;
                    document.getElementById("icon").value = data.icon;
                    // Show modal
                    hideSpinner()
                    const modal = new bootstrap.Modal(document.getElementById("categoryModal"));
                    modal.show();
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        /**
         * Submit form dynamically for Add or Edit
         */
        function submitForm() {
            const id = document.getElementById('id')?.value;
            const title = document.getElementById('title').value;
            const fileInput = document.getElementById('file');
            const file = fileInput.files[0];
            let url = '';
            let method = '';

            if (formType === 'add') {
                url = '/category';
                method = 'POST';
            } else {
                url = `/category/${id}`;
                method = 'PUT';
            }

            const formData = new FormData();
            formData.append('title', title);
            if (file) {
                formData.append('file', file);
            }

            if (method === 'POST' && formType !== 'add') {
                formData.append('_method', 'PUT');
            }

            showSpinner();

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    const modalEl = document.getElementById('categoryModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    hideSpinner();
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideSpinner();
                });
        }

        /**
         * Reset form fields
         */

        function resetForm() {
            document.getElementById("id").value = '';
            document.getElementById("title").value = '';
            document.getElementById("file").value = '';
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
