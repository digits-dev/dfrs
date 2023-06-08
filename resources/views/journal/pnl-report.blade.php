
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
                    <tr>
                        @if (!empty($revenues))
                            @foreach($revenues as $key => $rev)
                            <th>{{ $key }}</th>
                                @foreach($rev as $key_rev => $value_rev)
                                    <td>P {{ number_format($value_rev['amount'],2) }}</td>
                                @endforeach
                            @endforeach
                        @else
                            <th>REVENUE</th>
                            @foreach($cogs as $key => $cog)
                                @foreach($cog as $key_cog => $value_cog)
                                    <td> P 0.00</td>
                                @endforeach
                            @endforeach
                        @endif

                    </tr>

                    {{-- cogs --}}
                    @foreach($cogs as $key => $cog)
                    <tr>
                        <th>{{ $key }}</th>
                            @foreach($cogs as $key => $cog)
                                @foreach($cog as $key_cog => $value_cog)
                                    <td>P {{ number_format($value_cog['amount'],2) }}</td>
                                @endforeach
                            @endforeach
                    </tr>
                    @endforeach

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


</script>
@endpush
