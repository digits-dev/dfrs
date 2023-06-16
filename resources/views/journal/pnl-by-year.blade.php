
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

            <table class="table table-bordered" id="pnl-year">
                <thead>
                    <tr>
                        <th width="30%"></th>
                        @if(!empty($revenues) && count($revenues) != 0)
                            @foreach($revenues as $key => $rev)
                                <th class="text-center">{{ $rev->pnldate }}</th>
                            @endforeach
                            <th class="text-center">%</th>
                        @else
                            <th class="text-center">{{ $year }}</th>
                            <th class="text-center">%</th>
                        @endif
                    </tr>

                </thead>
                <tbody>
                    {{-- revenue --}}
                    <tr class="revenue">
                        @if(!empty($revenues) && count($revenues) != 0)
                        <th>REVENUE</th>
                            @foreach($revenues as $key => $rev)
                                <th class="revenue-amount">{{ number_format($rev->amount,2) }}</th>
                                <th>100%</th>
                            @endforeach
                        @else
                            <th>REVENUE</th>
                            <td colspan="0"><span>0.00</span></td>
                            <th>%</th>
                        @endif
                    </tr>

                    {{-- cogs --}}
                    @if(!empty($cogs) && count($cogs) != 0)
                        @foreach($cogs as $key => $cog)
                        <tr class="cogs">
                            <th>{{ $cog->chart_account_subtype }}</th>
                            <td class="cogs-amount" data-id="{{ $key }}">{{ number_format($cog->amount,2) }}</td>
                            <th class="cogs-percent"><span class="cogs-percentage-{{ $key }}">%</span></th>
                        </tr>
                        @endforeach
                    @else
                    <tr class="cogs">
                        <th>COST OF SALES</th>
                        <td colspan="0"><span>0.00</span></td>
                        <th>%</th>
                    </tr>
                    @endif

                    {{-- gross income --}}
                    <tr>
                        <th>GROSS INCOME</th>
                        @if(!empty($gross_income) && count($gross_income) != 0)
                        @foreach($gross_income as $key => $gi)
                            <th class="gincome"><span class="gross-income"> {{ number_format($gi->gross_income,2) }} </span></th>
                            <th class="gross-percent"><span class="gross-percentage">%</span></th>
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
                    @foreach($opex as $key => $opx)
                    <tr class="opex">
                        <td class="opex-name" data-name="{{ $opx->chart_account_subtype }}">{{ $opx->chart_account_subtype }}</td>
                        <td class="opex-amount" data-id="{{ $key }}">{{ number_format($opx->amount,2) }}</td>
                        <th><span class="opex-percentage-{{ $key }}">%</span></th>
                    </tr>
                    @endforeach
                    @else
                        <td></td>
                        <td colspan="0"><span>0.00</span></td>
                        <th>%</th>
                    @endif

                    {{-- opex total --}}
                    @if(!empty($opex_sum) && count($opex_sum) != 0)
                    @foreach($opex_sum as $key => $opx)
                    <tr class="opex-total">
                        <td></td>
                        <th class="opex-total-amount">{{ number_format($opx->sum_amount,2) }}</th>
                        <th><span class="opex-total-percentage">%</span></th>
                    </tr>
                    @endforeach
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
                    @foreach($otex as $key => $otx)
                    <tr>
                        <td>{{ $otx->chart_account_subtype }}</td>
                        <td class="otex-amount" data-id="{{ $key }}">{{ number_format($otx->amount,2) }}</td>
                        <th><span class="otex-percentage-{{ $key }}">%</span></th>
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

    document.title="PNL Report By Year";

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
