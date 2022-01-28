@extends('main')

@section('title', 'PPO-ARKA')

@section('breadcrumbs')
  <div class="breadcrumbs">
    <div class="col-sm-4">
      <div class="page-header float-left">
        <div class="page-title">
          <h1>{{ $title }}</h1>
        </div>
      </div>
    </div>
    <div class="col-sm-8">
      <div class="page-header float-right">
        <div class="page-title">
          <ol class="breadcrumb text-right">
            <li class="active"><i class="fa fa-th-large"></i></li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="content mt-3">
    <div class="animated fadeIn">

      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif

      <div class="card">
        <div class="card-header">
          <div class="pull-left">
            <strong>{{ $subtitle }}</strong>
          </div>
          <div class="pull-right">
            <a href="{{ url('unit_models/create') }}" class="btn btn-success btn-sm">
              <i class="fa fa-plus"></i> Add
            </a>
            <a href="{{ url('unit_models/import') }}" class="btn btn-primary btn-sm">
              <i class="fa fa-upload"></i> Import
            </a>
            <a href="{{ url('unit_models/export') }}" class="btn btn-warning btn-sm"><i class="fa fa-download"></i>
              Export</a>
          </div>
        </div>
        <div class="card-body table-responsive">
          <table id="unitmodel-table" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Model Name</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            {{-- <tbody>
              @foreach ($unitModels as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->model_no }}</td>
                  <td class="text-center">
                    <a href="{{ url('unit_models/' . $item->id . '/edit') }}" class="btn btn-primary btn-sm">
                      <i class="fa fa-pencil"></i>
                    </a>
                    <form action="{{ url('unit_models/' . $item->id) }}" method="post"
                      onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
                      @method('delete')
                      @csrf
                      <button class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody> --}}
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('style/assets/js/lib/data-table/datatables.min.js') }}"></script>
  <script>
    $(function() {
      $("#unitmodel-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('unit_models.index.data') }}',
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'model_no'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false,
            className: 'text-center'
          },
        ],
        fixedHeader: true,
      })
    });
  </script>
@endsection
