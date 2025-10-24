@extends('layout.layout')

@section('content')
<div class="container">
    <div class="page-inner">

        <!-- ================== PAGE HEADER ================== -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Categories</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i></a>
                </li>
                <li class="separator"><i class="bi bi-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Categories</a></li>
            </ul>
        </div>
        <!-- ================== END PAGE HEADER ================== -->


        <!-- ================== CATEGORIES TABLE ================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <!-- Card Header -->
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="card-title mb-0">Categories List</h5>

                        <!-- Add Category Button -->
                        <button 
                            type="button" 
                            class="btn btn-primary" 
                            data-bs-toggle="modal"
                            data-bs-target="#categoryModal" 
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
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Links</th>
                                    <th>Single/Multi</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $c)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <!-- Category Image -->
                                        <td>
                                            <img 
                                                src="{{ url($c->icon) }}" 
                                                style="height: 60px; width: 60px; border-radius: 50%;" 
                                                alt="category">
                                        </td>

                                        <td>{{ $c->title }}</td>

                                        <!-- Status Toggle -->
                                        <td>
                                            <form action="{{ route('category.status', $c->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ $c->isBlocked ? 'true' : 'false' }}">
                                                <label class="toggle-switch">
                                                    <input type="checkbox" onchange="this.form.submit()" {{ $c->isBlocked ? '' : 'checked' }}>
                                                    <span class="toggle-slider round"></span>
                                                </label>
                                            </form>
                                        </td>

                                        <!-- Links Type -->
                                        <td>{{ $c->isSingle ? 'Single' : 'Multiple' }}</td>

                                        <!-- Single/Multiple Toggle -->
                                        <td class="text-center">
                                            <form action="{{ route('category.single', $c->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="single" value="{{ $c->isSingle ? 'true' : 'false' }}">
                                                <label class="toggle-switch">
                                                    <input type="checkbox" onchange="this.form.submit()" {{ $c->isSingle ? '' : 'checked' }}>
                                                    <span class="toggle-slider round"></span>
                                                </label>
                                            </form>
                                        </td>

                                        <td>{{ $c->created_at->format('d M Y') }}</td>

                                        <!-- Action Buttons -->
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button 
                                                    class="btn btn-sm btn-outline-primary"
                                                    onclick="fetchData({{ $c->id }})">
                                                    Edit
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        {{ $categories->links() }}
                    </div>

                </div>
            </div>
        </div>
        <!-- ================== END CATEGORIES TABLE ================== -->


        <!-- ================== ADD / EDIT CATEGORY MODAL ================== -->
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fs-5 fw-semibold" id="categoryModalLabel">Add Category</h5>
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
                        <form id="categoryForm" onsubmit="submitForm(); return false;" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="id" name="id" />

                            <!-- Category Name -->
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Category Name</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-sm py-1" 
                                    id="title" 
                                    name="title"
                                    placeholder="Enter title" 
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
                                <img id="currentImage" src="" style="height:60px; width:60px; border-radius:50%;" />
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
            let formType = "add";

            // Prepare Add Modal
            function prepareAddForm() {
                formType = "add";
                resetForm();
                document.getElementById('categoryModalLabel').innerText = "Add Category";
                document.getElementById('formSubmitBtn').innerText = "Add";
            }

            // Fetch Data for Edit
            function fetchData(id) {
                showSpinner();
                formType = "edit";
                document.getElementById('categoryModalLabel').innerText = "Edit Category";
                document.getElementById('formSubmitBtn').innerText = "Update";

                fetch(`/category/${id}`)
                    .then(res => { if(!res.ok) throw new Error("Network error"); return res.json(); })
                    .then(data => {
                        document.getElementById("id").value = data.id;
                        document.getElementById("title").value = data.title;

                        if (data.icon) {
                            document.getElementById('currentImageWrapper').style.display = 'block';
                            document.getElementById('currentImage').src = `/${data.icon}`;
                        }

                        hideSpinner();
                        new bootstrap.Modal(document.getElementById("categoryModal")).show();
                    })
                    .catch(err => { console.error(err); hideSpinner(); });
            }

            // Submit Form
            function submitForm() {
                const id = document.getElementById('id').value;
                const title = document.getElementById('title').value;
                const file = document.getElementById('file').files[0];

                const formData = new FormData();
                formData.append('title', title);
                if(file) formData.append('file', file);

                let url = '/category';
                if(formType === 'edit') {
                    url = `/category/${id}`;
                    formData.append('_method', 'PUT');
                }

                showSpinner();
                fetch(url, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    showToastr(data.message, "Success");
                    hideSpinner();
                    location.reload();
                })
                .catch(err => { console.error(err); hideSpinner(); });
            }

            // Reset Form
            function resetForm() {
                document.getElementById("id").value = '';
                document.getElementById("title").value = '';
                document.getElementById("file").value = '';
                document.getElementById('currentImageWrapper').style.display = 'none';
                document.getElementById('currentImage').src = '';
            }
        </script>

        <!-- ================== TOASTR MESSAGES ================== -->
        @if(Session::has('success'))
            <script>document.addEventListener('DOMContentLoaded',()=>showToastr("{{ Session::get('success') }}","Success"));</script>
        @endif
        @if(Session::has('error'))
            <script>document.addEventListener('DOMContentLoaded',()=>showToastr("{{ Session::get('error') }}","Error"));</script>
        @endif

    </div>
</div>
@endsection