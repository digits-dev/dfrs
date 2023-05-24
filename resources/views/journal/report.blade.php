
@extends('crudbooster::admin_template')
@section('content')

@push('head')

@endpush

<div class='panel panel-default'>
    <form method='post' id="form" enctype="multipart/form-data" action="{{ route('fs.generate-report') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class='panel-body'>
        <div class="col-md-4">
            Type
            <select name="type" id="type" class="form-control type" title="Type" required>
                <option value="">Please select type</option>
                <option value="pnl">PNL</option>
                <option value="bs">BS</option>
            </select>
        </div>

        <div class="col-md-4">
            Year
            <select name="year" id="year" class="form-control" title="year">
                <option value="">Please select year</option>
            </select>
        </div>

        <div class="col-md-4">
            Company
            <select name="company" id="company" class="form-control" title="company">
                <option value="">Please select company</option>
                <option value="dtc">DIGITS TRADING CORP</option>
            </select>
        </div>

        {{-- <div class="col-md-3">
            Brand
            <select name="brand" id="brand" class="form-control" title="brand">
                <option value="">Please select brand</option>
            </select>
        </div> --}}

        {{-- <div class="col-md-3">
            Category
            <select name="category" id="category" class="form-control" title="category">
                <option value="">Please select category</option>
            </select>
        </div> --}}

    </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-primary pull-right">Generate</button>
    </div>
    </form>
</div>

@endsection

@push('bottom')
<script type="text/javascript">

        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        let currentYear = new Date().getFullYear();
        let earliestYear = 2019;
        var to_dy = new Date();
        var selected_ch = 0;

        while (currentYear >= earliestYear) {
            $('#year').append($('<option>').val(currentYear).text(currentYear));
            currentYear -= 1;
        }


        for (var m = 0; m < 12; m++) {
            $('#month').append($('<option>').val(m+1).text(monthNames[m]));
        }


        $('#year').change(function () {

            if($("#year").val() == to_dy.getFullYear()){
                $('#month').empty();
                $('#month').append($('<option>').val('').text("Please select month"));
                for (var m = 0; m < (to_dy.getMonth()+1); m++) {
                    $('#month').append($('<option>').val(m+1).text(monthNames[m]));
                }
            }

        });
</script>
@endpush
