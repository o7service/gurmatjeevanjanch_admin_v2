@extends('layout.layout')

@section('content')
<div class="container">
    <div class="page-inner">

        <!-- ================== PAGE HEADER ================== -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Singers</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i></a>
                </li>
                <li class="separator"><i class="bi bi-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Singers</a></li>
            </ul>
        </div>
        <!-- ================== END PAGE HEADER ================== -->


        <!-- ================== SINGERS TABLE ================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <!-- Card Header -->
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="card-title mb-0">Singers List</h5>

                        <!-- Add Singer Button -->
                        <button 
                            type="button" 
                            class="btn btn-primary" 
                            data-bs-toggle="modal"
                            data-bs-target="#singerLinkModal" 
                            onclick="prepareAddForm()">
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
                                    <th>Audios</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($singerImages as $t)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $t->name }}</td>
                                        <td>
                                            <img 
                                                src="{{ $t->imageUrl ? url($t->imageUrl) : asset('image.png') }}"
                                                style="height: 60px; width: 60px; border-radius: 50%;" 
                                                alt="singer">
                                        </td>

                                        <!-- Status Toggle -->
                                        <td>
                                            <div class="col">
                                                <form action="{{ route('singer.status', $t->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status"
                                                        value="{{ $t->isBlocked ? 'true' : 'false' }}">
                                                    <label class="toggle-switch">
                                                        <input 
                                                            type="checkbox" 
                                                            onchange="this.form.submit()" 
                                                            {{ $t->isBlocked ? '' : 'checked' }}>
                                                        <span class="toggle-slider round"></span>
                                                    </label>
                                                </form>
                                            </div>
                                        </td>

                                        <td>{{ $t->created_at }}</td>

                                        <!-- Action Buttons -->
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button 
                                                    onclick="fetchData({{ $t->id }})"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Edit
                                                </button>
                                                {{-- Optional Delete Button
                                                <button 
                                                    onclick="deleteSinger({{ $t->id }})"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button> 
                                                --}}
                                            </div>
                                        </td>

                                        <!-- Manage Audios Button -->
                                        <td>
                                            <a href="/audios/{{$t->id}}">
                                                <button class="btn btn-sm btn-outline-primary">Manage</button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        {{ $singerImages->links() }}
                    </div>

                </div>
            </div>
        </div>
        <!-- ================== END SINGERS TABLE ================== -->


        <!-- ================== ADD / EDIT SINGER MODAL ================== -->
        <div class="modal fade" id="singerLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="singerLinkModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fs-5 fw-semibold" id="singerLinkModalLabel">Add Singer</h5>
                        <button 
                            type="button" 
                            class="btn-close" 
                            data-bs-dismiss="modal" 
                            aria-label="Close"
                            onclick="resetForm()">
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body pt-1">
                        <form id="singerForm" onsubmit="submitForm(); return false;" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="id" name="id" />

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Name</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-sm py-1" 
                                    id="name" 
                                    name="name"
                                    placeholder="Enter Name" 
                                    required 
                                    style="font-size: 0.8rem;" />
                            </div>

                            <!-- Image Upload -->
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
                                <img 
                                    id="currentImage" 
                                    src="" 
                                    style="height:60px; width:60px; border-radius:50%;" />
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer border-0 pt-0">
                                <button type="reset" onclick="resetForm()" class="btn btn-sm btn-danger px-3">Reset</button>
                                <button type="submit" id="formSubmitBtn" class="btn btn-sm btn-primary px-3">Save</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- ================== END MODAL ================== -->


        <!-- ================== JAVASCRIPT ================== -->
        <script>
            // Track whether the form is in Add or Edit mode
            let formType = "add";

            /**
             * Prepare modal for adding a new singer
             */
            function prepareAddForm() {
                formType = "add";
                resetForm();
                document.getElementById('singerLinkModalLabel').innerText = "Add Singer";
                document.getElementById('formSubmitBtn').innerText = "Add";
            }

            /**
             * Fetch singer data for editing
             */
            function fetchData(id) {
                showSpinner();
                formType = "edit";
                document.getElementById('singerLinkModalLabel').innerText = "Edit Singer";
                document.getElementById('formSubmitBtn').innerText = "Update";

                fetch(`/singer/${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error("Network response was not ok");
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById("id").value = data.id;
                        document.getElementById("name").value = data.name;

                        if (data.imageUrl) {
                            const wrapper = document.getElementById('currentImageWrapper');
                            wrapper.style.display = 'block';
                            document.getElementById('currentImage').src = `/${data.imageUrl}`;
                        }

                        hideSpinner();
                        const modal = new bootstrap.Modal(document.getElementById("singerLinkModal"));
                        modal.show();
                    })
                    .catch(error => console.error("Error fetching data:", error));
            }

            /**
             * Submit form dynamically (Add or Edit)
             */
            function submitForm() {
                const id = document.getElementById('id').value;
                const name = document.getElementById('name').value;
                const fileInput = document.getElementById('file');
                const file = fileInput.files[0];

                const formData = new FormData();
                formData.append('name', name);
                if (file) formData.append('file', file);

                let url = '/singer'; // default add URL
                if (formType === 'edit') {
                    url = `/singer/update/${id}`;
                    formData.append('_method', 'PUT');
                }

                showSpinner();
                fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                .then(async response => {
                    const text = await response.text();
                    try {
                        const data = JSON.parse(text);
                        if (!response.ok) throw new Error(data.message || "Unknown error");
                        return data;
                    } catch (e) {
                        console.error("Non-JSON response:", text);
                        throw new Error("Server returned invalid response. Check console.");
                    }
                })
                .then(data => {
                    console.log('Success:', data);
                    const modalEl = document.getElementById('singerLinkModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    hideSpinner();
                    location.reload();
                    showToastr(data.message, "Success");
                })
                .catch(error => console.error('Error:', error));
            }

            /**
             * Reset form fields
             */
            function resetForm() {
                document.getElementById("id").value = '';
                document.getElementById("name").value = '';
                document.getElementById("file").value = '';
                document.getElementById('currentImageWrapper').style.display = 'none';
                document.getElementById('currentImage').src = '';
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

    </div>
</div>
@endsection
