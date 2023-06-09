
@extends('crudbooster::admin_template')
@section('content')

@push('head')
<style type="text/css">

    table.table.table-bordered td {
      border: 1px solid black;
    }

    table.table.table-bordered tr {
      border: 1px solid black;
    }

    table.table.table-bordered th {
      border: 1px solid black;
    }
</style>
@endpush

<div class='panel panel-default'>
    {{-- <form method='post' id="form" enctype="multipart/form-data" action="{{ route('fs.generate-report') }}"> --}}
        {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
    <div class='panel-body'>
        <div class="col-md-12">
            <div class="text-center">
                <h4> <b>DIGITS PNL 2023</b> </h4>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="30%"></th>
                        @foreach($cogs as $key => $cog)
                            @foreach($cog as $key_cog => $value_cog)
                                <th> {{ $value_cog['pnldate'] }}</th>
                            @endforeach
                        @endforeach
                    </tr>

                </thead>
                <tbody>
                    <tr class="revenue">
                        @if (!empty($revenues))
                            @foreach($revenues as $key => $rev)
                            <th>{{ $key }}</th>
                                @foreach($rev as $key_rev => $value_rev)
                                    <td class="revenue-amount" data-id="{{ $value_rev['pnldate'] }}">P {{ number_format($value_rev['amount'],2) }}</td>
                                @endforeach
                            @endforeach
                        @else
                            <th>REVENUE</th>
                            @foreach($cogs as $key => $cog)
                                @foreach($cog as $key_cog => $value_cog)
                                    <td class="revenue-amount"> P 0.00</td>
                                @endforeach
                            @endforeach
                        @endif
                    </tr>

                    {{-- cogs --}}
                    @foreach($cogs as $key => $cog)
                    <tr class="cogs">
                        <th>{{ $key }}</th>
                            @foreach($cogs as $key => $cog)
                                @foreach($cog as $key_cog => $value_cog)
                                    <td class="cogs-amount" data-id="{{ $value_cog['pnldate'] }}">P {{ number_format($value_cog['amount'],2) }}</td>
                                @endforeach
                            @endforeach
                    </tr>
                    @endforeach

                    {{-- gross income --}}
                    <tr>
                        <th>GROSS INCOME</th>
                    @foreach($gross_income as $key => $gi)
                        @foreach($cogs as $key_cog => $cog)
                                <td class="income {{ $key }}">P {{ number_format($gi['amount'],2) }}</td>
                        @endforeach
                    @endforeach
                    </tr>

                    {{-- opex --}}
                    <tr>
                        <th colspan="100%">OPERATING EXPENSE</th>
                    </tr>
                    @foreach($opex as $key => $opx)
                    <tr>
                        <td>{{ $key }}</td>
                            @foreach($cogs as $key => $cog)
                                @foreach($cog as $key_cog => $value_cog)
                                    <td>P {{ number_format($opx[$key_cog]['amount'],2) }}</td>
                                @endforeach
                            @endforeach
                    </tr>
                    @endforeach

                    {{-- otex --}}
                    <tr>
                        <th colspan="100%">OTHER EXPENSE</th>
                    </tr>
                    @foreach($otex as $key => $otx)
                    <tr>
                        <td>{{ $key }}</td>
                            @foreach($cogs as $key => $cog)
                                @foreach($cog as $key_cog => $value_cog)
                                    <td>P {{ number_format($otx[$key_cog]['amount'],2) }}</td>
                                @endforeach
                            @endforeach
                    </tr>
                    @endforeach
            </table>

        </div>

    </div>
    <div class="panel-footer" style="overflow: auto;">
        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-file-text"> </i> Export</button>
    </div>
    {{-- </form> --}}
</div>

@endsection

@push('bottom')
<script type="text/javascript">
    let sum = 0;
    let row = {}

    $(".table tr").each(function (index) {
        let revenue = $(this).find('td.revenue-amount');
        let cogs = $(this).find('td.cogs-amount');

        revenue = Number(revenue.text().replace(/[^0-9.]/g, ''));
        cogs = Number(cogs.text().replace(/[^0-9.]/g, ''));
        row[index] = revenue - cogs;
        // const primary = $(this).find('td.revenue-amount');
        // console.log(primary.text());
        // sum += Number(primary.text().replace(/[^0-9.]/g, ''));
    })

    console.log(row);

</script>
@endpush
