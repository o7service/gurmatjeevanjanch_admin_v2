  @extends('layout.admin_layout')
  @section('content')
      <div class="container">
          <div class="page-inner">
              <div class="page-header">
                  <h3 class="fw-bold mb-3">Category</h3>
                  <ul class="breadcrumbs mb-3">
                      <li class="nav-home">
                          <a href="#">
                              <i class="icon-home"></i>
                          </a>
                      </li>
                      <li class="separator">
                          <i class="icon-arrow-right"></i>
                      </li>
                      <li class="nav-item">
                          <a href="#">Dashboard</a>
                      </li>
                      <li class="separator">
                          <i class="icon-arrow-right"></i>
                      </li>
                      <li class="nav-item">
                          <a href="#">Manage</a>
                      </li>
                  </ul>
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <div class="card">
                          <div class="card-header">
                              <div class="card-title">Add New Category</div>
                          </div>
                          <form action="{{ route('category.store') }}" method="post" enctype="multipart/form-data">
                              @csrf
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-md-12 col-lg-12">
                                          <div class="form-group">
                                              <label for="email2">Category Name</label>
                                              <input type="text" class="form-control" name="name"
                                                  placeholder="Enter name" required />

                                          </div>
                                          <div class="form-group">
                                              <label for="email2">Category Image</label>
                                              <input type="file" required class="form-control" name="image" />

                                          </div>
                                          <div class="form-group">
                                              <label for="password">Description (optional)</label>

                                              <textarea class="form-control" name="description" id="password" placeholder="Enter category description"></textarea>
                                          </div>


                                      </div>

                                  </div>
                              </div>
                              <div class="card-action">
                                  <button class="btn btn-success" type="submit">Submit</button>
                                  <button class="btn btn-danger" type="reset">Reset</button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  @endsection
