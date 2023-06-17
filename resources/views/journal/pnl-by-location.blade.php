
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
                <h4> <b>PNL YEAR {{ $year }}</b> </h4>
            </div>

            <table class="table table-bordered" id="pnl-location">
                <thead>
                    <tr>
                        <th class="text-center" width="30%">{{ $location }}</th>
                        @foreach($columnYear as $key => $year)
                            <th class="text-center">{{ $year }}</th>
                            <th>%</th>
                        @endforeach
                    </tr>

                </thead>
                <tbody>
                    {{-- revenue --}}
                    <tr class="revenue">
                        @if(!empty($revenues) && count($revenues) != 0)
                        <th>REVENUE</th>
                        @foreach($columnYear as $key => $rev)
                            <td class="revenue-amount" data-id="{{ $rev }}">{{ number_format($revenues_data[0]->$rev,2) }}</td>
                            <th>%</th>
                        @endforeach
                        @else
                            <th>REVENUE</th>
                            @foreach($columnYear as $key => $rev)
                            <td colspan="0"><span>0.00</span></td>
                            <th>%</th>
                            @endforeach
                        @endif
                    </tr>

                    {{-- cogs --}}
                    @if(!empty($cogs) && count($cogs) != 0)
                    <tr class="cogs">
                        <th>{{ $cogs_data[0]->chart_account_subtype }}</th>
                        @foreach($columnYear as $key => $cog)
                            <td class="cogs-amount" data-id="{{ $cog }}">{{ number_format($cogs_data[0]->$cog,2) }}</td>
                            <th>%</th>
                        @endforeach
                    </tr>
                    @else
                    <tr class="cogs">
                        <th>COST OF SALES</th>
                        @foreach($columnYear as $key => $cpg)
                            <td colspan="0"><span>0.00</span></td>
                            <th>%</th>
                        @endforeach
                    </tr>
                    @endif

                    {{-- gross income --}}
                    <tr>
                        <th>GROSS INCOME</th>
                        @if(!empty($gross_income) && count($gross_income) != 0)
                        @foreach($gross_income as $key => $gi)
                            @if($columnYear[$key] == $gi->pnldate)
                            <th class="gincome"><span class="gross-income"> {{ number_format($gi->gross_income,2) }} </span></th>
                            <th class="gross-percent"><span class="gross-percentage">%</span></th>
                            @endif
                        @endforeach
                        @else
                            <td colspan="0"><span>0.00</span></td>
                            <th>%</th>
                        @endif
                    </tr>

                    {{-- opex --}}
                    <tr>
                        <th colspan="100%">OPERATING EXPENSE</th>
                    </tr>
                    @if(!empty($opex) && count($opex) != 0)
                    @foreach($opex_data as $key => $opx)
                    <tr>
                        <td>{{ $opx->chart_account_subtype }}</td>
                        @foreach($columnYear as $key => $opexDate)
                            <td class="opex-amount" data-id="{{ $opexDate }}">{{ number_format($opx->$opexDate,2) }}</td>
                            <th>%</th>
                        @endforeach
                    </tr>
                    @endforeach
                    @else
                        <td></td>
                        <td colspan="0"><span>0.00</span></td>
                        <th>%</th>
                    @endif

                    {{-- opex total --}}
                    @if(!empty($opex_sum) && count($opex_sum) != 0)
                    <tr class="opex-total">
                        <td></td>
                        @foreach($opex_sum as $key => $opx)
                            @if($columnYear[$key] == $opx->pnldate)
                            <th class="opex-total-amount">{{ number_format($opx->sum_amount,2) }}</th>
                            <th><span class="opex-total-percentage">%</span></th>
                            @endif
                        @endforeach
                    </tr>
                    @else
                        <td></td>
                        <td colspan="0"><span>0.00</span></td>
                        <th>%</th>
                    @endif

                    {{-- noi --}}
                    <tr>
                        <th>NET OPERATING INCOME</th>
                        <th><span class="noi-amount">0.00</span></th>
                        <th><span class="noi-percentage">%</span></th>
                    </tr>

                    {{-- otex --}}
                    <tr>
                        <th colspan="100%">OTHER EXPENSE</th>
                    </tr>
                    @if(!empty($otex) && count($otex) != 0)
                    @foreach($otex_data as $key => $otx)
                    <tr>
                        <td>{{ $otx->chart_account_subtype }}</td>
                        @foreach($columnYear as $key => $otexDate)
                            <td class="otex-amount" data-id="{{ $otexDate }}">{{ number_format($otx->$otexDate,2) }}</td>
                            <th>%</th>
                        @endforeach
                    </tr>
                    @endforeach
                    @else
                        <td></td>
                        <td colspan="0"><span>0.00</span></td>
                        <th>%</th>
                    @endif

                    {{-- ebitda --}}
                    <tr>
                        <th>EBITDA</th>
                        <th><span class="ebitda-amount">0.00</span></th>
                        <th><span class="ebitda-percentage">%</span></th>
                    </tr>

                    {{-- total-expense --}}
                    <tr>
                        <th>TOTAL EXPENSE</th>
                        <th><span class="total-expense-amount">0.00</span></th>
                        <th><span class="total-expense-percentage">%</span></th>
                    </tr>
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

    document.title="PNL Report By Location";

    let rev = parseFloat($('.revenue-amount').text().replace(/[^0-9]*\,/g, ''));
    let gross = parseFloat($('.gross-income').html().replace(/[^0-9]*\,/g, ''));
    let opexTotal = parseFloat($('.opex-total-amount').html().replace(/[^0-9]*\,/g, ''));
    let gross_percentage = gross/rev;
    let opex_percentage = opexTotal/rev;
    let noi = gross-opexTotal;
    let noi_percentage = noi/rev;

    $('.gross-percentage').html(gross_percentage.toFixed(2)+'%');
    $('.opex-total-percentage').html(opex_percentage.toFixed(2)+'%');
    $('.noi-amount').html(Number(parseFloat(noi).toFixed(2)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    $('.noi-percentage').html(noi_percentage.toFixed(2)+'%');

    let cogsPercentage = [];
    let opexPercentage = [];
    let otexPercentage = [];
    let depreciation = 0;
    let otherExpense = 0;

    var pnlTable = document.getElementById('pnl-year');
    if(pnlTable) {
        Array.from(pnlTable.rows).forEach((tr, row_ind) => {
            Array.from(tr.cells).forEach((cell, col_ind) => {
                let cellValue = cell.textContent;

                if(cell.className == 'cogs-amount'){
                    cogsPercentage[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''))/rev;
                }

                if(cell.className == 'opex-amount'){
                    opexPercentage[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''))/rev;
                }

                if(cell.className == 'opex-name' && cell.attributes[1].textContent == "DEPRECIATION"){
                    depreciation = cell.textContent;
                }

                if(cell.className == 'otex-amount'){
                    otexPercentage[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''))/rev;
                    otherExpense += parseFloat(cellValue.replace(/[^0-9]*\,/g, ''));
                }

            });
        });
    }

    for (let i = 0; i < cogsPercentage.length; i++) {
        if(!isNaN(cogsPercentage[i])){
            $('.cogs-percentage-'+i).html(cogsPercentage[i].toFixed(2)+'%');
        }
    }

    for (let i = 0; i < opexPercentage.length; i++) {
        if(!isNaN(opexPercentage[i])){
            $('.opex-percentage-'+i).html(opexPercentage[i].toFixed(2)+'%');
        }
    }

    for (let i = 0; i < otexPercentage.length; i++) {
        if(!isNaN(otexPercentage[i])){
            $('.otex-percentage-'+i).html(otexPercentage[i].toFixed(2)+'%');
        }
    }

    let ebitda = noi+depreciation;
    let ebitda_percentage = ebitda/rev;
    $('.ebitda-amount').html(Number(parseFloat(ebitda).toFixed(2)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    $('.ebitda-percentage').html(ebitda_percentage.toFixed(2)+'%');


    let totalExpense = opexTotal+otherExpense;
    let totalExpense_percentage = totalExpense/rev;
    $('.total-expense-amount').html(Number(parseFloat(totalExpense).toFixed(2)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    $('.total-expense-percentage').html(totalExpense_percentage.toFixed(2)+'%');

</script>
@endpush
