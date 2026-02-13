@extends('layout.layout')

@section('content')
    <div class="container">
        <div class="page-inner">

            <!-- ================== PAGE HEADER ================== -->
            <div class="page-header">
                <h3 class="fw-bold mb-3">{{ $category->title }} Links</h3>

                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('dashboard') }}">
                            <i class="bi bi-house-fill"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="bi bi-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">{{ $category->title }} Links</a>
                    </li>
                </ul>
            </div>
            <!-- ================== END PAGE HEADER ================== -->


            <!-- ================== LINKS TABLE ================== -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <!-- Card Header -->
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                            <h5 class="card-title mb-0">{{ $category->title }} List</h5>

                            @if (!$category->isSingle)
                                <!-- Add New Link Button -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#linksLinkModal" onclick="prepareAddForm()">
                                    <i class="bi bi-plus-lg"></i> ADD
                                </button>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-head-bg-primary">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Link</th>
                                            {!! $isYoutube ? '<th>Live</th>' : '' !!}
                                            {!! $isYoutube ? '<th>Thumbnail</th>' : '' !!}
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
                                                <td>
                                                    <a href="{{ $t->link }}" target="_blank">Link</a>
                                                </td>

                                                <!-- Live Toggle (Only for YouTube Category) -->
                                                {!! $isYoutube ? '
                                                                        <td>
                                                                            <div class="col">
                                                                                <form action="' . route('links.live', $t->id) . '" method="POST">
                                                                                    ' . csrf_field() . '
                                                                                    ' . method_field('PUT') . '
                                                                                    <input type="hidden" name="live" value="' . ($t->isLive ? 'true' : 'false') . '">

                                                                                    <label class="toggle-switch">
                                                                                        <input type="checkbox" onchange="this.form.submit()" ' . ($t->isLive ? 'checked' : '') . '>
                                                                                        <span class="toggle-slider round"></span>
                                                                                    </label>
                                                                                </form>
                                                                            </div>
                                                                        </td>
                                                                    ' : '' !!}


                                                @if($isYoutube)
                                                    <td>
                                                        @if(!empty($t->thumbnail))
                                                            <a href="{{ url($t->thumbnail) }}" target="_blank">
                                                                <img src="{{ url($t->thumbnail) }}" style="height: 36px; width: 60px;"
                                                                    alt="thumbnail">
                                                            </a>
                                                        @else
                                                            <span class="text-muted small">No Image</span>
                                                        @endif
                                                    </td>
                                                @endif

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
                                                            Edit
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            {{ $links->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- ================== END LINKS TABLE ================== -->

        </div>
    </div>


    <!-- ================== ADD / EDIT LINK MODAL ================== -->
    <div class="modal fade" id="linksLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="linksLinkModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fs-5 fw-semibold" id="linksLinkModalLabel">Add Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="resetForm()">
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body pt-1">
                    <form id="linksForm" onsubmit="submitForm(); return false;">
                        @csrf

                        <!-- Hidden Fields -->
                        <input type="hidden" id="id" name="id" />
                        <input type="hidden" id="categoryId" name="categoryId" value="{{ $category->id }}" />

                        <!-- Title Field -->
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Title</label>
                            <input type="text" class="form-control form-control-sm py-1" id="title" name="title"
                                placeholder="Enter title" required />
                        </div>

                        <!-- Link URL Field -->
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Link URL</label>
                            <input type="url" class="form-control form-control-sm py-1" id="link" name="link"
                                placeholder="Enter URL" required />
                        </div>

                        <!-- Live Toggle (Only for YouTube Category) -->
                        {!! $isYoutube ? '
                                                            <div class="mb-3">
                                                                <label class="form-label small text-muted mb-1">Image</label>
                                                                <input 
                                                                    type="file" 
                                                                    class="form-control form-control-sm py-1" 
                                                                    id="file" 
                                                                    name="file" 
                                                                    accept=".jpg,.jpeg,.png" 
                                                                    style="font-size: 0.8rem;" />
                                                            </div>

                                                            <!-- Current Image Preview -->
                                                            <div class="mb-3" id="currentImageWrapper" style="display:none;">
                                                                <label class="form-label small text-muted mb-1">Current Image</label>
                                                                <img id="currentImage" src="" style="height:60px; width:60px; border-radius:50%;" />
                                                            </div>
                                                        ' : '' !!}

                        <!-- Current Icon Preview -->
                        <div class="mb-3" id="currentIconWrapper" style="display:none;">
                            <label class="form-label small text-muted mb-1">Current Icon</label>
                            <img id="currentIcon" src="" style="height:60px; width:60px; border-radius:50%;" />
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer border-0 pt-0">
                            <button type="reset" onclick="resetForm()" class="btn btn-sm btn-danger px-3">
                                Reset
                            </button>
                            <button type="submit" id="formSubmitBtn" class="btn btn-sm btn-primary px-3">
                                Save
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- ================== END MODAL ================== -->


    <!-- ================== JAVASCRIPT ================== -->
    <script>
        let formType = "add";

        /**
         * Prepare modal for adding a new link
         */
        function prepareAddForm() {
            formType = "add";
            resetForm();
            document.getElementById('linksLinkModalLabel').innerText = "Add {{ $category->title }} Link";
            document.getElementById('formSubmitBtn').innerText = "Add";
        }

        /**
         * Fetch existing data and prepare modal for editing
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
                    document.getElementById("id").value = data.id;
                    document.getElementById("title").value = data.title;
                    document.getElementById("link").value = data.link;
                    hideSpinner();
                    const modal = new bootstrap.Modal(document.getElementById("linksLinkModal"));
                    modal.show();
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        /**
         * Submit form dynamically (Add or Edit)
         */
        function submitForm() {
            const id = document.getElementById('id').value;
            const title = document.getElementById('title').value;
            const link = document.getElementById('link').value;
            const categoryId = document.getElementById('categoryId').value;
            const fileInput = document.getElementById('file');

            let url = '';
            let method = 'POST'; // Always POST when using FormData for Laravel

            if (formType === 'add') {
                url = '/links';
            } else {
                url = `/links/${id}`;
            }

            const formData = new FormData();
            formData.append('title', title);
            formData.append('link', link);
            formData.append('categoryId', categoryId);

            if (formType !== 'add') {
                formData.append('_method', 'PUT');
            }

            if (fileInput && fileInput.files.length > 0) {
                formData.append('file', fileInput.files[0]);
            }

            showSpinner();

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(async response => {

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            showValidationErrors(data.errors);
                        } else {
                            showToastr(data.message || "Something went wrong", "Error");
                        }
                        throw new Error("Validation failed");
                    }

                    return data;
                })
                .then(data => {
                    const modalEl = document.getElementById('linksLinkModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    hideSpinner();
                    showToastr(data.message, "Success");

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                })
                .catch(error => {
                    hideSpinner();
                });
        }

        function showValidationErrors(errors) {

            // Clear old errors
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            for (let field in errors) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.classList.add('invalid-feedback');
                    errorDiv.innerText = errors[field][0];
                    input.parentNode.appendChild(errorDiv);
                }
            }
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


    <!-- ================== TOASTR MESSAGES ================== -->
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