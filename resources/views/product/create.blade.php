  @extends('layout.admin_layout')
  @section('content')
      <div class="container">
          <div class="page-inner">
              <div class="page-header">
                  <h3 class="fw-bold mb-3">Product</h3>
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
                              <div class="card-title">Add New Product</div>
                          </div>
                          <form action="{{ route('product.store') }}" method="post">
                              @csrf
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-md-12 col-lg-12">
                                          <div class="form-group">
                                              <label for="email2">Choose Category</label>
                                              <select type="number" class="form-control" name="category_id"
                                                  placeholder="Enter name" required>
                                                  <option selected disabled> Choose Category</option>

                                                  @foreach ($categories as $c)
                                                      <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                  @endforeach


                                              </select>

                                          </div>
                                          <div class="form-group">
                                              <label for="email2">Product Name</label>
                                              <input type="text" class="form-control" name="name"
                                                  placeholder="Enter name" required />

                                          </div>
                                          <div class="form-group">
                                              <label for="email2">Price</label>
                                              <input type="number" class="form-control" name="price"
                                                  placeholder="Enter name" required />

                                          </div>


                                          <div class="form-group">
                                              <label for="password">Description (optional)</label>

                                              <textarea class="form-control" name="description" id="password" placeholder="Enter Product description"></textarea>
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
