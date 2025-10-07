@extends('layout.layout')
@section('content')
    <div class="container">
        <div class="page-inner">
            <!-- Page Header -->
            <div class="page-header">
                <h3 class="fw-bold mb-3">{{ $category->title }} Links</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">{{ $category->title }} Links</a></li>
                </ul>
            </div>


            <!--  Links Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- Card Header -->
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                            <h5 class="card-title mb-0">{{ $category->title }} List</h5>
                            <!-- Add Button -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#linksLinkModal" onclick="prepareAddForm()">
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
                                        <th>Link</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($links as $t)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $t->title }}</td>
                                            <td>{{ $t->link }}</td>
                                            <!-- Status Toggle -->
                                            <td>
                                                <div class="col">
                                                    <form action="{{ route('links.status', $t->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status"
                                                            value="{{ $t->isBlocked ? 'true' : 'false' }}">
                                                        <label class="toggle-switch">
                                                            <input type="checkbox" onchange="this.form.submit()" {{ $t->isBlocked ? '' : 'checked' }}>
                                                            <span class="toggle-slider round"></span>
                                                        </label>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ $t->created_at }}</td>
                                            <!-- Action Buttons -->
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button onclick="fetchData({{ $t->id }})"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <!-- <button onclick="deleteLink({{ $t->id }})"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </button> -->
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $links->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Link Modal -->
    <div class="modal fade" id="linksLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="linksLinkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fs-5 fw-semibold" id="linksLinkModalLabel">Add Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="resetForm()"></button>
                </div>
                <div class="modal-body pt-1">
                    <form id="linksForm" onsubmit="submitForm(); return false;">
                        @csrf
                        <input type="hidden" id="id" name="id" />
                        <input type="hidden" id="categoryId" name="categoryId" value="{{ $category->id }}" />

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Title</label>
                            <input type="text" class="form-control form-control-sm py-1" id="title" name="title"
                                placeholder="Enter title" required style="font-size: 0.8rem;" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Link URL</label>
                            <input type="url" class="form-control form-control-sm py-1" id="link" name="link"
                                placeholder="Enter URL" required style="font-size: 0.8rem;" />
                        </div>

                        <div class="mb-3" id="currentIconWrapper" style="display:none;">
                            <label class="form-label small text-muted mb-1">Current Icon</label>
                            <img id="currentIcon" src="" style="height:60px; width:60px; border-radius:50%;" />
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
            document.getElementById('linksLinkModalLabel').innerText = "Add {{ $category->title }} Link";
            document.getElementById('formSubmitBtn').innerText = "Add";
        }

        /**
         * Fetch existing data and prepare the modal for editing
         */
        function fetchData(id) {

            showSpinner();
            formType = "edit";
            document.getElementById('linksLinkModalLabel').innerText = "Edit {{ $category->title }} Link";
            document.getElementById('formSubmitBtn').innerText = "Update";

            fetch(`/links/show/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    // Populate modal fields with fetched data
                    document.getElementById("id").value = data.id;
                    document.getElementById("title").value = data.title;
                    document.getElementById("link").value = data.link;
                    // Show modal
                    hideSpinner()
                    const modal = new bootstrap.Modal(document.getElementById("linksLinkModal"));
                    modal.show();
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        /**
         * Submit form dynamically for Add or Edit
         */
        function submitForm() {
            const id = document.getElementById('id').value;
            const title = document.getElementById('title').value;
            const link = document.getElementById('link').value;
            const categoryId = document.getElementById('categoryId').value;
            let url = '';
            let method = '';
            if (formType === 'add') {
                url = '/links';
                method = 'POST';
            } else {
                url = `/links/${id}`;
                method = 'PUT';
            }
            showSpinner();
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ title: title, link: link, categoryId: categoryId })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // showToastr(data.message, "Success");
                    const modalEl = document.getElementById('linksLinkModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    hideSpinner();
                    // setTimeout(() => {
                    // }, 2100)
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
        }

        /**
         * Reset form fields
         */

        function resetForm() {
            document.getElementById("id").value = '';
            document.getElementById("title").value = '';
            document.getElementById("link").value = '';
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