
@extends('crudbooster::admin_template')
@section('content')

  <div class='panel panel-default'>
      <div class='panel-body'>

        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {!! $errors->all() !!}
        </div>

        @endif

        @if (session()->has('failures'))
        <div class="alert alert-danger" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <table class="table table-bordered">
                <tr>
                    <th>Row</th>
                    <th>Attribute</th>
                    <th>Errors</th>
                    <th>Value</th>
                </tr>

                @foreach (session()->get('failures') as $validation)
                    <tr>
                        <td>{{ $validation->row() }}</td>
                        <td>{{ $validation->attribute() }}</td>
                        <td>
                            <ul>
                                @foreach ($validation->errors() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            {{ $validation->values()[$validation->attribute()] }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        @endif

          <form method='post' id='form' enctype='multipart/form-data' action='{{$uploadRoute}}'>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="box-body">
                  <div class='callout callout-success'>
                      <h4>Welcome to Data Importer Tool</h4>
                      Before uploading a file, please read below instructions : <br />
                      * File format should be : CSV file format<br />
                      * Don't include items not found in submaster <br />
                      * Don't include duplicate records<br />

                  </div>

                  <table class="table table-striped">
                    <thead>
                        <tr>
                          <th scope="col">Import Template File</th>
                          <th scope="col">File to Import</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><a href='{{ $uploadTemplate }}' class='btn btn-primary' role='button'>Download Template</a></td>
                          <td><input type='file' name='import_file' id='file_name' class='form-control' required accept='.csv' />
                            <div class='help-block'>File type supported only : CSV</div></td>
                        </tr>

                  </table>


                </div>
                  </div>
                  <div class='panel-footer'>
                      <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
                      <input type='submit' class='btn btn-primary pull-right' value='Upload' />
                  </div>
          </form>
      </div>

@endsection

@push('bottom')
<script type="text/javascript">

</script>
@endpush
