@extends('layout.admin_layout')
@section('content')
    <div class="container">
        <div class="page-inner">
            <!-- Page Header -->
            <div class="page-header">
                <h3 class="fw-bold mb-3">Youtube Links</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Youtube Links</a></li>
                </ul>
            </div>

            <!-- Youtube Links Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- Card Header -->
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                            <h5 class="card-title mb-0">Youtube Links List</h5>
                            <!-- Add Button -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#youtubeLinkModal" onclick="prepareAddForm()">
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
                                        <th>Live</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($youtubeLinks as $t)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $t->title }}</td>
                                            <td>{{ $t->link }}</td>
                                            <!-- Live Toggle -->
                                            <td>
                                                <div class="col">
                                                    <form action="{{ route('youtube.live', $t->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status"
                                                            value="{{ $t->isLive ? 'true' : 'false' }}">
                                                        <label class="toggle-switch">
                                                            <input type="checkbox" onchange="this.form.submit()" {{ $t->isLive ? 'checked' : '' }}>
                                                            <span class="toggle-slider round"></span>
                                                        </label>
                                                    </form>
                                                </div>
                                            </td>
                                            <!-- Status Toggle -->
                                            <td>
                                                <div class="col">
                                                    <form action="{{ route('youtube.status', $t->id) }}" method="POST">
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
                            {{ $youtubeLinks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="youtubeLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="youtubeLinkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm ">
            <form id="youtubeForm" onsubmit="submitForm(); return false;">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="youtubeLinkModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="resetForm()"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" />
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Enter title" required />
                                </div>
                                <div class="form-group">
                                    <label for="link">Link</label>
                                    <input type="url" class="form-control" id="link" name="link" placeholder="Enter URL"
                                        required />
                                </div>
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
            document.getElementById('youtubeLinkModalLabel').innerText = "Add Youtube Link";
            document.getElementById('formSubmitBtn').innerText = "Add";
        }

        /**
         * Fetch existing data and prepare the modal for editing
         */
        function fetchData(id) {
            showSpinner();
            formType = "edit";
            document.getElementById('youtubeLinkModalLabel').innerText = "Edit Youtube Link";
            document.getElementById('formSubmitBtn').innerText = "Update";

            fetch(`/youtube/${id}`)
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
                    const modal = new bootstrap.Modal(document.getElementById("youtubeLinkModal"));
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
            let url = '';
            let method = '';
            if (formType === 'add') {
                url = '/youtube';
                method = 'POST';
            } else {
                url = `/youtube/${id}`;
                method = 'PUT';
            }
            showSpinner();
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ title: title, link: link })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // showToastr(data.message, "Success");
                    const modalEl = document.getElementById('youtubeLinkModal');
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