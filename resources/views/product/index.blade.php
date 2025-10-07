  @extends('layout.admin_layout')
  @section('content')
      <div class="container">
          <div class="page-inner">
              <div class="page-header">
                  <h3 class="fw-bold mb-3">Products</h3>
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
                          <a href="#">Add Product</a>
                      </li>
                  </ul>
              </div>


              <div class="row">
                  <div class="col text-info">
                      @if (Session::get('success'))
                          {{ Session::get('success') }}
                      @endif
                  </div>
              </div>
              <div class="row">

                  <div class="col-md-12">


                      <div class="card">
                          <div class="card-header">
                              <div class="card-title">Products List</div>
                          </div>
                          <div class="card-body">
                              <table class="table table-head-bg-primary">
                                  <thead>
                                      <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">Name</th>
                                          <th scope="col">price</th>
                                          <th scope="col">Category</th>
                                          <th scope="col">Description</th>
                                          <th scope="col">Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>

                                      @foreach ($products as $product)
                                          <tr>
                                              <td>
                                                  {{ $loop->iteration }}
                                              </td>
                                              <td>{{ $product->name }}</td>
                                              <td>{{ $product->price }}</td>
                                              <td>{{ $product->category->name }}</td>

                                              <td> {{ $product->description }}</td>
                                              <td>
                                                  <a href="{{ route('product.edit', $product->id) }}">
                                                      <button class="btn btn-sm btn-outline-primary">
                                                          <i class="bi bi-pencil-square"></i>
                                                      </button>
                                                  </a>
                                              </td>
                                          </tr>
                                      @endforeach


                                  </tbody>
                              </table>

                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  @endsection
