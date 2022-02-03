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
            <li class="active"><i class="fa fa-check-square"></i></li>
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

      <div class="card" id="accordionFlushExample">
        <div class="card-header" id="flush-headingOne" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
          <strong class="card-title" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
            aria-controls="flush-collapseOne">
            <i class="fa fa-search"> Filter</i>
          </strong>
        </div>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
          data-bs-parent="#accordionFlushExample">
          <div class="card-body card-block">
            {{-- <form action="" method="get"> --}}
            <div class="row form-group">
              <div class="col-3">
                <div class="form-group">
                  <label for="company" class=" form-control-label">From</label>
                  <input type="date" class="form-control" name="date1" id="date1" value="{{ request('date1') }}">
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="vat" class=" form-control-label">To</label>
                  <input type="date" class="form-control" name="date2" id="date2" value="{{ request('date2') }}">
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="street" class=" form-control-label">NIK</label>
                  <input type="text" class="form-control" name="nik" id="nik" value="{{ request('nik') }}">
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="street" class=" form-control-label">Name</label>
                  <input type="text" class="form-control" name="name" id="name" value="{{ request('name') }}">
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="street" class=" form-control-label">Category</label>
                  <select name="category" class="form-control" id="category">
                    <option value="">- All -</option>
                    @foreach ($category as $cat => $data)
                      <option value="{{ $data->code }}" {{ request('category') == $data->code ? 'selected' : '' }}>
                        {{ $data->code }} - {{ $data->remarks }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            {{-- </form> --}}
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="pull-left">
            <strong>{{ $subtitle }}</strong>
          </div>
          <div class="pull-right">
            <a href="{{ url('attendances/trash') }}" class="btn btn-danger btn-sm">
              <i class="fa fa-trash"></i> Trash
            </a>
            <a href="{{ url('attendances/create') }}" class="btn btn-success btn-sm">
              <i class="fa fa-plus"></i> Add
            </a>
          </div>
        </div>
        <div class="card-body table-responsive">
          {{-- @dd($attendances) --}}
          <table id="attendances-table" class="table table-striped table-bordered" width=100%>
            <thead>
              <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Employee Name</th>
                <th>Time</th>
                <th>Category</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            {{-- <tbody>
              @foreach ($attendances as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->nik }}</td>
                  <td>{{ $item->name }}</td>
                  <td>{{ $item->project->code }} - {{ $item->project->name }}</td>
                  <td class="text-center">
                    <a href="{{ url('attendances/' . $item->id) }}" class="btn btn-warning btn-sm">
                      <i class="fa fa-eye"></i>
                    </a>
                    <a href="{{ url('attendances/' . $item->id . '/edit') }}" class="btn btn-primary btn-sm">
                      <i class="fa fa-pencil"></i>
                    </a>
                    <form action="{{ url('attendances/' . $item->id) }}" method="post"
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
  {{-- <script>
    $(function() {
      $("#attendances-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('attendances.index.data') }}',
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'nik'
          },
          {
            data: 'name'
          },
          {
            data: 'time'
          },
          {
            data: 'category'
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
  </script> --}}

  <script type="text/javascript">
    $(function() {
      var table = $('#attendances-table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Blrtip',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        ajax: {
          url: "{{ route('attendances.index') }}",
          data: function(d) {
            d.nik = $('#nik').val(),
              d.name = $('#name').val(),
              d.date1 = $('#date1').val(),
              d.date2 = $('#date2').val(),
              d.category = $('#category').val(),
              d.search = $('input[type="search"]').val()
          }
        },
		length: 10,
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'nik',
            name: 'nik'
          },
          {
            data: 'name',
            name: 'name'
          },
          {
            data: 'time',
            name: 'time'
          },
          {
            data: 'category',
            name: 'category'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false,
            className: 'text-center'
          },
        ]
      });
      $('#date1, #date2, #nik, #name, #category').keyup(function() {
        table.draw();
      });
      $('#date1, #date2, #category').change(function() {
        table.draw();
      });
    });
  </script>
@endsection
