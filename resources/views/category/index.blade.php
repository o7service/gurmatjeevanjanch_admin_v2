  @extends('layout.admin_layout')
  @section('content')
  <div class="container">
      <div class="page-inner">
          <div class="page-header">
              <h3 class="fw-bold mb-3">Categories</h3>
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
                      <a href="#">Add Category</a>
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
                          <div class="card-title">Categories List</div>
                      </div>
                      <div class="card-body">
                          <table class="table table-head-bg-primary">
                              <thead>
                                  <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Name</th>
                                      <th scope="col">image</th>
                                      <th scope="col">Description</th>
                                      <th scope="col">Action</th>
                                  </tr>
                              </thead>
                              <tbody>

                                  @foreach ($categories as $category)
                                  <tr>
                                      <td>
                                          {{ $loop->iteration }}
                                      </td>
                                      <td>{{ $category->name }}</td>
                                      <td>
                                          <img src="{{ url('category_images', $category->image) }}"
                                              style="height: 60px;width:60px; border-radius: 50%;" class=""
                                              alt="">

                                      </td>
                                      <td> {{ $category->description }}</td>
                                      <td>
                                          <div class="d-flex gap-2 ">
                                              <a href="{{ route('category.edit', $category->id) }}">
                                                  <button class="btn btn-sm btn-outline-primary">
                                                      <i class="bi bi-pencil-square"></i>
                                                  </button>
                                              </a>


                                              <form action=" {{ route('category.destroy', $category->id) }}"
                                                  method="post">
                                                  @method('DELETE')
                                                  @csrf
                                                  <button class="btn btn-sm btn-outline-danger" type="submit">
                                                      <i class="bi bi-trash3"></i>
                                                  </button>

                                              </form>
                                      </td>
                      </div>

                      </tr>
                      @endforeach
                      </tbody>
                      </table>
                      {{ $categories->links() }}
                  </div>
              </div>
          </div>
      </div>
  </div>
  </div>
  @endsection