@extends('layout.layout')

@section('content')
    <div class="container">
        <div class="page-inner">

            <!-- ================== PAGE HEADER ================== -->
            <div class="page-header">
                <h3 class="fw-bold mb-3">Programs</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i></a>
                    </li>
                    <li class="separator"><i class="bi bi-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Programs</a></li>
                </ul>
            </div>
            <!-- ================== END PAGE HEADER ================== -->


            <!-- ================== PROGRAMS LINKS TABLE ================== -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <!-- Card Header -->
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                            <h5 class="card-title mb-0">Programs List</h5>

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
                                        <th>Image</th>
                                        <th>Map Link</th>
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

                                            <!-- Image -->
                                            <td>
                                                <img src="{{ $t->imageUrl ? url($t->imageUrl) : asset('image.png') }}"
                                                    style="height:60px;width:60px;border-radius:50%;" alt="program">
                                            </td>

                                            <!-- Map Link -->
                                            <td><a href="{{ $t->mapLink }}" target="_blank">View Map</a></td>

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

                                            <td>{{ $t->created_at->format('d M Y') }}</td>

                                            <!-- Edit Button -->
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    onclick="fetchData({{ $t->id }})">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $programsLinks->links() }}
                        </div>

                    </div>
                </div>
            </div>
            <!-- ================== END TABLE ================== -->


            <!-- ================== ADD / EDIT MODAL ================== -->
            <div class="modal fade" id="programsLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form id="programsForm" onsubmit="submitForm(); return false;" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fs-5" id="programsLinkModalLabel">Add Programs Link</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    onclick="resetForm()">
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <input type="hidden" id="id" name="id" />
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            placeholder="Enter title" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            placeholder="Enter address" required />
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Details</label>
                                        <textarea class="form-control" id="details" name="details"
                                            placeholder="Enter details" rows="3"></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Map Link</label>
                                        <input type="url" class="form-control" id="mapLink" name="mapLink"
                                            placeholder="Enter map URL" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Image</label>
                                        <input type="file" class="form-control" id="file" name="file"
                                            accept=".png,.jpg,.jpeg" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="startDate" name="startDate" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="endDate" name="endDate" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Contact Number 1</label>
                                        <input type="text" class="form-control" id="contactNumber1" name="contactNumber1"
                                            placeholder="Enter contact number 1" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Contact Number 2</label>
                                        <input type="text" class="form-control" id="contactNumber2" name="contactNumber2"
                                            placeholder="Enter contact number 2" />
                                    </div>

                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer border-0 pt-0">
                                <button type="reset" onclick="resetForm()" class="btn btn-danger px-3">Reset</button>
                                <button type="submit" id="formSubmitBtn" class="btn btn-primary px-3">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <!-- ================== END MODAL ================== -->


            <!-- ================== JAVASCRIPT ================== -->

            <!-- Tiny MCE for details -->
            <script>
                tinymce.init({
                    selector: '#details',
                    height: 150,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar: 'undo redo | formatselect | ' +
                        'bold italic backcolor | alignleft aligncenter ' +
                        'alignright alignjustify | bullist numlist outdent indent | ' +
                        'removeformat | help',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
                });
            </script>


            <script>
                let formType = "add";

                // Prepare Add Modal
                function prepareAddForm() {
                    formType = "add";
                    resetForm();
                    document.getElementById('programsLinkModalLabel').innerText = "Add Programs Link";
                    document.getElementById('formSubmitBtn').innerText = "Add";
                }

                // Fetch Data for Edit
                function fetchData(id) {
                    showSpinner();
                    formType = "edit";
                    document.getElementById('programsLinkModalLabel').innerText = "Edit Programs Link";
                    document.getElementById('formSubmitBtn').innerText = "Update";

                    fetch(`/programs/${id}`)
                        .then(res => { if (!res.ok) throw new Error("Network error"); return res.json(); })
                        .then(data => {
                            document.getElementById("id").value = data.id;
                            document.getElementById("title").value = data.title;
                            document.getElementById("address").value = data.address;
                            tinymce.get('details').setContent(data.details || '');
                            document.getElementById("mapLink").value = data.mapLink;
                            document.getElementById("startDate").value = data.startDate;
                            document.getElementById("endDate").value = data.endDate;
                            document.getElementById("contactNumber1").value = data.contactNumber1;
                            document.getElementById("contactNumber2").value = data.contactNumber2;

                            hideSpinner();
                            new bootstrap.Modal(document.getElementById("programsLinkModal")).show();
                        })
                        .catch(err => { console.error(err); hideSpinner(); });
                }

                // Submit Form
                function submitForm() {
                    const detailsContent = tinymce.get('details').getContent({ format: 'text' }).trim();
                    if (!detailsContent) {
                        showToastr("Please enter details.", "Error");
                        return;
                    }

                    const id = document.getElementById('id').value;
                    const formData = new FormData(document.getElementById('programsForm'));
                    formData.set('details', tinymce.get('details').getContent());
                    let url = formType === 'add' ? '/programs/add' : `/programs/update/${id}`;

                    if (formType === 'edit') formData.append('_method', 'PUT');

                    console.log(url, formData)

                    showSpinner();
                    fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    })
                        .then(async res => {
                            const text = await res.text();
                            try { return JSON.parse(text); }
                            catch { console.error("Non-JSON response:", text); throw new Error("Invalid server response"); }
                        })
                        .then(data => {
                            showToastr(data.message, "Success");
                            hideSpinner();
                            location.reload();
                        })
                        .catch(err => { console.error(err); hideSpinner(); });
                }

                // Reset Form
                function resetForm() {
                    document.getElementById('programsForm').reset();
                    document.getElementById('id').value = '';
                }
            </script>


            <!-- ================== TOASTR MESSAGES ================== -->
            @if(Session::has('success'))
                <script>document.addEventListener('DOMContentLoaded', () => showToastr("{{ Session::get('success') }}", "Success"));</script>
            @endif
            @if(Session::has('error'))
                <script>document.addEventListener('DOMContentLoaded', () => showToastr("{{ Session::get('error') }}", "Error"));</script>
            @endif

        </div>
    </div>
@endsection