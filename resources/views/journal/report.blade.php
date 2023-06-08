
@extends('crudbooster::admin_template')
@section('content')

@push('head')

@endpush

<div class='panel panel-default'>
    <form method='post' id="form" enctype="multipart/form-data" action="{{ route('fs.generate-report') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class='panel-body'>
        <div class="col-md-4">
            <div class="col-md-12">
                <b> Report Type </b>
                <select name="type" id="type" class="form-control type" title="Type" required>
                    <option value="">Please select report type</option>
                    <option value="year">BY YEAR</option>
                    <option value="month">BY MONTH</option>
                    <option value="quarter">BY QUARTER</option>
                    <option value="location">BY LOCATION</option>
                    <option value="company">BY COMPANY</option>
                    <option value="department">BY DEPARTMENT</option>
                    <option value="channel">BY CHANNEL</option>
                    <option value="concepts">BY CONCEPTS</option>
                </select>
            </div>
            <div class="col-md-12">
                <b> FS Type </b>
                <select name="type" id="type" class="form-control type" title="Type" required>
                    <option value="">Please select FS type</option>
                    <option value="pnl">PNL</option>
                    <option value="bs">BS</option>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <b> Year </b>
            <select name="year" id="year" class="form-control" title="year">
                <option value="">Please select year</option>
            </select>
        </div>

        <div class="col-md-4">
            <b> Company </b>
            <select name="company" id="company" class="form-control" title="company">
                <option value="">Please select company</option>
                <option value="DIGITS">DIGITS TRADING CORP</option>
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
    <div class="panel-footer" style="overflow: auto;">
        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-file-text"> </i> Generate</button>
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
