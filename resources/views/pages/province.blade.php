@extends('layouts.app')

@section('link')
<link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            {{ session('success') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="row d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">Provinces</h5>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#provinceModal">
                            New
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-stripe table-sm" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th>Name</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($row as $p)
                                    <tr>
                                        <td>{{ $p->name }}</td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-secondary mx-1" data-toggle="modal" data-target="#provinceModal" data-province="{{ $p }}"><i class="far fa-edit"></i></button>
                                                <form action="{{ route('provinces.destroy', $p->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" onclick="return confirm('Do you really want to remove the form?');"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="provinceModal"  tabindex="-1"  role="dialog" aria-labelledby="provinceModalLabel" aria-hidden="true" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="provinceModalLabel">
                                Add New Province
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('provinces.store') }}" method="POST" id="submitForm">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                    <input type="text" class="form-control" name="name" placeholder="Province name..." id="name" />
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        Form();
        $('#dataTable').DataTable({
            "ordering": false,
            responsive: true
        });
    });

    function Form() {
        $('#provinceModal').on('show.bs.modal', function(e) {
            let dataset = e.relatedTarget.dataset.province;
            if (dataset != undefined) {
              let province = JSON.parse(dataset);
              $('#name').val(province.name);
              $('input[name="_method"]').val('PATCH');
              $('#submitForm').attr('action', `/configure/provinces/${province.id}`);
              $('#provinceModalLabel').text('Edit Record');
            }
            else {
              $('#name').val('');
              $('#submitForm').attr('action','');
              $('#provinceModalLabel').text('Create Record');
            }           
          });
    }
</script>
@endsection
