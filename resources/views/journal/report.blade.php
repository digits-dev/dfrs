
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
                <select name="report_type" id="report_type" class="form-control report-type" title="Type" required>
                    <option value="">Please select report type</option>
                    {{-- <option value="year">BY YEAR</option> --}}
                    {{-- <option value="month">BY MONTH</option> --}}
                    {{-- <option value="quarter">BY QUARTER</option> --}}
                    <option value="company-year">BY COMPANY - YEAR</option>
                    <option value="company-month">BY COMPANY - MONTH</option>
                    <option value="company-qtr">BY COMPANY - QUARTER</option>

                    <option value="" disabled>------</option>

                    <option value="location-year">BY LOCATION - YEAR</option>
                    <option value="location-month">BY LOCATION - MONTH</option>
                    <option value="location-qtr">BY LOCATION - QUARTER</option>

                    <option value="" disabled>------</option>

                    <option value="department-year">BY DEPARTMENT - YEAR</option>
                    <option value="department-month">BY DEPARTMENT - MONTH</option>
                    <option value="department-qtr">BY DEPARTMENT - QUARTER</option>

                    {{-- <option value="channel">BY CHANNEL</option> --}}
                    {{-- <option value="concepts">BY CONCEPTS</option> --}}
                </select>
            </div>
            <div class="col-md-12">
                <b> FS Type </b>
                <select name="fs_type" id="fs_type" class="form-control fs-type" title="Type" required>
                    <option value="">Please select FS type</option>
                    <option value="pnl">PNL</option>
                    <option value="bs">BS</option>
                </select>
            </div>
        </div>

        <div class="col-md-8">

            <div class="col-md-4">
                <b> Year </b>
                <select name="year" id="year" class="form-control" title="year" disabled>
                    <option value="">Please select year</option>
                </select>
            </div>

            <div class="col-md-4">
                <b> Company </b>
                <select name="company" id="company" class="form-control" title="company" disabled>
                    <option value="">Please select company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->inter_company_name }}">{{ $company->inter_company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <b> Location </b>
                <select name="location" id="location" class="form-control" title="location" disabled>
                    <option value="">Please select location</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->location_name }}">{{ $location->location_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <b> Month </b>
                <select name="month" id="month" class="form-control" title="month" disabled>
                    <option value="">Please select month</option>
                </select>
            </div>

            <div class="col-md-4">
                <b> Quarter </b>
                <select name="quarter" id="quarter" class="form-control" title="quarter" disabled>
                    <option value="">Please select quarter</option>
                    <option value="1">Q1</option>
                    <option value="2">Q2</option>
                    <option value="3">Q3</option>
                    <option value="4">Q4</option>
                </select>
            </div>

            <div class="col-md-4">
                <b> Department </b>
                <select name="department" id="department" class="form-control" title="department" disabled>
                    <option value="">Please select department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                    @endforeach
                </select>
            </div>

        </div>



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

    $('#report_type').change(function(){
        let reportType = $(this).val();
        switch (reportType) {
            case 'company-year':
                $('#year').removeAttr('disabled');
                $('#year').prop('required',true);

                $('#company').removeAttr('disabled');
                $('#company').prop('required',true);
            break;

            case 'company-month':
                $('#year').removeAttr('disabled');
                $('#year').prop('required',true);

                $('#company').removeAttr('disabled');
                $('#company').prop('required',true);

                $('#month').removeAttr('disabled');
                $('#month').prop('required',true);
            break;

            case 'company-qtr':
                $('#year').removeAttr('disabled');
                $('#year').prop('required',true);

                $('#company').removeAttr('disabled');
                $('#company').prop('required',true);

                $('#quarter').removeAttr('disabled');
                $('#quarter').prop('required',true);
            break;

            case 'location-year':
                $('#year').removeAttr('disabled');
                $('#year').prop('required',true);

                $('#location').removeAttr('disabled');
                $('#location').prop('required',true);

            break;

            case 'location-month':
                $('#year').removeAttr('disabled');
                $('#year').prop('required',true);

                $('#month').removeAttr('disabled');
                $('#month').prop('required',true);

                $('#location').removeAttr('disabled');
                $('#location').prop('required',true);
            break;

            case 'location-qtr':
                $('#year').removeAttr('disabled');
                $('#year').prop('required',true);

                $('#quarter').removeAttr('disabled');
                $('#quarter').prop('required',true);

                $('#location').removeAttr('disabled');
                $('#location').prop('required',true);
            break;
            // department
            case 'department-year':
                $('#year').removeAttr('disabled');
                $('#year').prop('required',true);

                $('#department').removeAttr('disabled');
                $('#department').prop('required',true);
            break;

            default:
                break;
        }
    });



</script>
@endpush
