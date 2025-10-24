@extends('layout.layout')

@section('content')
<div class="container">
    <div class="page-inner">

        <!-- ================== PAGE HEADER ================== -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">{{ $singer->name }} Audios</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-house-fill"></i>
                    </a>
                </li>
                <li class="separator"><i class="bi bi-arrow-right"></i></li>
                <li class="nav-item"><a href="/singer">Singers</a></li>
                <li class="separator"><i class="bi bi-arrow-right"></i></li>
                <li class="nav-item"><a href="#">{{ $singer->name }} Audios</a></li>
            </ul>
        </div>
        <!-- ================== END PAGE HEADER ================== -->


        <!-- ================== AUDIOS TABLE ================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <!-- Card Header -->
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="card-title mb-0">{{ $singer->name }} Audios List</h5>

                        <!-- Add Audio Button -->
                        <button 
                            type="button" 
                            class="btn btn-primary" 
                            data-bs-toggle="modal"
                            data-bs-target="#audiosLinkModal" 
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
                                    <th>Title</th>
                                    <th>Link</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($audios as $t)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $t->title }}</td>
                                        <td>
                                            <a href="{{ $t->audioLink }}" target="_blank">Audio</a>
                                        </td>

                                        <!-- Status Toggle -->
                                        <td>
                                            <div class="col">
                                                <form action="{{ route('audios.status', $t->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input 
                                                        type="hidden" 
                                                        name="status"
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
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <!-- ================== END AUDIOS TABLE ================== -->

    </div>
</div>


<!-- ================== ADD / EDIT AUDIO MODAL ================== -->
<div class="modal fade" id="audiosLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="audiosLinkModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fs-5 fw-semibold" id="audiosLinkModalLabel">Add Audio</h5>
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
                <form id="audiosForm" onsubmit="submitForm(); return false;">
                    @csrf

                    <!-- Hidden Fields -->
                    <input type="hidden" id="id" name="id" />
                    <input type="hidden" id="singerId" name="singerId" value="{{ $singer->id }}" />

                    <!-- Title Field -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Title</label>
                        <input 
                            type="text" 
                            class="form-control form-control-sm py-1" 
                            id="title" 
                            name="title"
                            placeholder="Enter title" 
                            required 
                            style="font-size: 0.8rem;" />
                    </div>

                    <!-- Audio Link Field -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Link URL</label>
                        <input 
                            type="url" 
                            class="form-control form-control-sm py-1" 
                            id="link" 
                            name="link"
                            placeholder="Enter Audio URL" 
                            required 
                            style="font-size: 0.8rem;" />
                    </div>

                    <!-- Current Icon Preview (Optional) -->
                    <div class="mb-3" id="currentIconWrapper" style="display:none;">
                        <label class="form-label small text-muted mb-1">Current Icon</label>
                        <img 
                            id="currentIcon" 
                            src="" 
                            style="height:60px; width:60px; border-radius:50%;" />
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button 
                            type="reset" 
                            onclick="resetForm()" 
                            class="btn btn-sm btn-danger px-3">
                            Reset
                        </button>
                        <button 
                            type="submit" 
                            id="formSubmitBtn" 
                            class="btn btn-sm btn-primary px-3">
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
    // Track whether the form is in Add or Edit mode
    let formType = "add";

    /**
     * Prepare modal for adding a new audio
     */
    function prepareAddForm() {
        formType = "add";
        resetForm();
        document.getElementById('audiosLinkModalLabel').innerText = "Add {{ $singer->name }}'s Audio";
        document.getElementById('formSubmitBtn').innerText = "Add";
    }

    /**
     * Fetch existing audio data for editing
     */
    function fetchData(id) {
        showSpinner();
        formType = "edit";
        document.getElementById('audiosLinkModalLabel').innerText = "Edit {{ $singer->name }}'s Audio";
        document.getElementById('formSubmitBtn').innerText = "Update";

        fetch(`/audios/show/${id}`)
            .then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
            })
            .then(data => {
                document.getElementById("id").value = data.id;
                document.getElementById("title").value = data.title;
                document.getElementById("link").value = data.audioLink;
                hideSpinner();
                const modal = new bootstrap.Modal(document.getElementById("audiosLinkModal"));
                modal.show();
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    /**
     * Submit form dynamically (Add or Edit mode)
     */
    function submitForm() {
        const id = document.getElementById('id').value;
        const title = document.getElementById('title').value;
        const link = document.getElementById('link').value;
        const singerId = document.getElementById('singerId').value;

        let url = '';
        let method = '';

        if (formType === 'add') {
            url = '/audios';
            method = 'POST';
        } else {
            url = `/audios/${id}`;
            method = 'PUT';
        }

        showSpinner();

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ title, link, singerId })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);

            // Close modal and reload
            const modalEl = document.getElementById('audiosLinkModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            hideSpinner();
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    /**
     * Reset all form fields
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