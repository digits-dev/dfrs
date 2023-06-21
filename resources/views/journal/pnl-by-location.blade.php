
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
                            <th>100%</th>
                        @endforeach
                        @else
                            <th>REVENUE</th>
                            @foreach($columnYear as $key => $rev)
                            <td class="revenue-amount" data-id="{{ $rev }}">0.00</td>
                            <th>%</th>
                            @endforeach
                        @endif
                    </tr>

                    {{-- cogs --}}
                    @if(!empty($cogs) && count($cogs) != 0)
                    <tr class="cogs">
                        <th>{{ $cogs_data[0]->chart_account_subtype }}</th>
                        @foreach($columnYear as $key => $year)
                            <td class="cogs-amount" data-id="{{ $year }}">{{ number_format($cogs_data[0]->$year,2) }}</td>
                            <th><span class="cogs-percentage-{{ $year }}">%</span></th>
                        @endforeach
                    </tr>
                    @else
                    <tr class="cogs">
                        <th>COST OF SALES</th>
                        @foreach($columnYear as $key => $year)
                            <td class="cogs-amount" data-id="{{ $year }}">0.00</td>
                            <th><span class="cogs-percentage-{{ $year }}">%</</th>
                        @endforeach
                    </tr>
                    @endif

                    {{-- gross income --}}
                    <tr>
                        <th>GROSS INCOME</th>
                        @foreach($columnYear as $key => $year)
                            <th class="gross-amount-{{ $year }}" data-id="{{ $year }}">0.00</th>
                            <th><span class="gross-percentage-{{ $year }}">%</span></th>
                        @endforeach
                    </tr>

                    {{-- opex --}}
                    <tr>
                        <th colspan="100%">OPERATING EXPENSE</th>
                    </tr>
                    @if(!empty($opex) && count($opex) != 0)
                    @foreach($opex_data as $key => $opx)
                    <tr>
                        <td class="opex-name">{{ $opx->chart_account_subtype }}</td>
                        @foreach($columnYear as $keyYear => $opexYear)
                            <td class="opex-amount" data-id="{{ $opexYear }}" data-opex="{{ $key }}" data-name="{{ $opx->chart_account_subtype }}">{{ number_format($opx->$opexYear,2) }}</td>
                            <th><span class="opex-percentage-{{ $opexYear }}{{ $key }}">%</span></th>
                        @endforeach
                    </tr>
                    @endforeach
                    @else
                        <td></td>
                        @foreach($columnYear as $key => $year)
                            <td colspan="0"><span>0.00</span></td>
                            <th>%</th>
                        @endforeach
                    @endif

                    {{-- opex total --}}
                    @if(!empty($opex_sum) && count($opex_sum) != 0)
                    <tr class="opex-total">
                        <td></td>
                        @foreach($opex_sum as $key => $opx)
                            @if($columnYear[$key] == $opx->pnldate)
                            <th class="opex-total-amount" data-id="{{ $columnYear[$key] }}">{{ number_format($opx->sum_amount,2) }}</th>
                            <th><span class="opex-total-percentage-{{ $columnYear[$key] }}">%</span></th>
                            @endif
                        @endforeach
                    </tr>
                    @else
                    @endif

                    {{-- noi --}}
                    <tr>
                        <th>NET OPERATING INCOME</th>
                        @if(!empty($columnYear) && count($columnYear) != 0)
                            @foreach($columnYear as $key => $year)
                                <th><span class="noi-amount-{{ $year }}">0.00</span></th>
                                <th><span class="noi-percentage-{{ $year }}">%</span></th>
                            @endforeach
                        @else
                            <th colspan="0"></th>
                        @endif
                    </tr>

                    {{-- otex --}}
                    <tr>
                        <th colspan="100%">OTHER EXPENSE</th>
                    </tr>
                    @if(!empty($otex) && count($otex) != 0)
                    @foreach($otex_data as $key => $otx)
                    <tr>
                        <td>{{ $otx->chart_account_subtype }}</td>
                        @foreach($columnYear as $keyYear => $otexYear)
                            <td class="otex-amount" data-id="{{ $otexYear }}" data-otex="{{ $key }}">{{ number_format($otx->$otexYear,2) }}</td>
                            <th><span class="otex-percentage-{{ $otexYear }}{{ $key }}">%</span></th>
                        @endforeach
                    </tr>
                    @endforeach

                    <tr style="display: none">
                        <td></td>
                        @foreach($otex_sum as $key => $otxSum)
                            @if($columnYear[$key] == $otxSum->pnldate)
                            <th class="otex-total-amount" data-id="{{ $columnYear[$key] }}">{{ number_format($otxSum->sum_amount,2) }}</th>
                            <th></th>
                            @endif
                        @endforeach
                    </tr>

                    @else
                        <th></th>
                        <td colspan="0"><span>0.00</span></td>
                    @endif

                    {{-- ebitda --}}
                    <tr>
                        <th>EBITDA</th>
                        @foreach($columnYear as $key => $year)
                            <th><span class="ebitda-amount-{{ $year }}">0.00</span></th>
                            <th><span class="ebitda-percentage-{{ $year }}">%</span></th>
                        @endforeach

                    </tr>

                    {{-- total-expense --}}
                    <tr>
                        <th>TOTAL EXPENSE</th>
                        @foreach($columnYear as $key => $year)
                            <th><span class="total-expense-amount-{{ $year }}">0.00</span></th>
                            <th><span class="total-expense-percentage-{{ $year }}">%</span></th>
                        @endforeach
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

    let revAmount = [];
    let grossAmount = [];
    let opexTotalAmount = [];
    let cogsAmount = [];
    let cogsPercentage = [];
    let opexPercentage = [];
    let opexTotalPercentage = [];
    let otexPercentage = [];
    let depreciation = [];
    let otherExpense = [];

    var pnlTable = document.getElementById('pnl-location');
    if(pnlTable) {
        Array.from(pnlTable.rows).forEach((tr, row_ind) => {
            Array.from(tr.cells).forEach((cell, col_ind) => {
                let cellValue = cell.textContent;

                if(cell.className == 'revenue-amount'){
                    revAmount[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''));
                }

                if(cell.className == 'cogs-amount'){
                    cogsPercentage[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''))/revAmount[cell.attributes[1].textContent];
                    cogsAmount[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''));
                }

                if(cell.className == 'opex-amount'){
                    opexPercentage[cell.attributes[1].textContent+cell.attributes[2].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''))/revAmount[cell.attributes[1].textContent];
                    if(cell.attributes[3].textContent == "DEPRECIATION"){
                        depreciation[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''));
                    }
                }

                if(cell.className == 'otex-amount'){
                    otexPercentage[cell.attributes[1].textContent+cell.attributes[2].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''))/revAmount[cell.attributes[1].textContent];
                }

                if(cell.className == 'opex-total-amount'){
                    opexTotalAmount[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''));
                    opexTotalPercentage[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''))/revAmount[cell.attributes[1].textContent];
                }

                if(cell.className == 'otex-total-amount'){
                    otherExpense[cell.attributes[1].textContent] = parseFloat(cellValue.replace(/[^0-9]*\,/g, ''));
                }

            });
        });
    }

    Object.keys(revAmount).forEach(key => {
        let grossA = parseFloat(revAmount[key]) - parseFloat(cogsAmount[key]);
        grossAmount[key] = grossA;
        let grossP = (grossA/revAmount[key])*100;
        $('.gross-amount-'+key).html(Number(parseFloat(grossA).toFixed(2)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('.gross-percentage-'+key).html(grossP.toFixed(2)+'%');
    });

    Object.keys(cogsPercentage).forEach(key => {
        let cogP = cogsPercentage[key]*100;
        $('.cogs-percentage-'+key).html(cogP.toFixed(2)+'%');
    });

    Object.keys(opexPercentage).forEach(key => {
        let opexP = opexPercentage[key]*100;
        $('.opex-percentage-'+key).html(opexP.toFixed(2)+'%');
    });

    Object.keys(otexPercentage).forEach(key => {
        let otexP = otexPercentage[key]*100;
        $('.otex-percentage-'+key).html(otexP.toFixed(2)+'%');
    });

    Object.keys(opexTotalPercentage).forEach(key => {
        let opxTotalP = opexTotalPercentage[key]*100;
        $('.opex-total-percentage-'+key).html(opxTotalP.toFixed(2)+'%');
    });

    Object.keys(grossAmount).forEach(key => {
        let grossA = grossAmount[key];
        let opexA = opexTotalAmount[key];
        let noiA = grossA-opexA;
        let noiP = (noiA/revAmount[key])*100;
        let depA = parseFloat(depreciation[key]);
        let ebitda = parseFloat(noiA);

        $('.noi-amount-'+key).html(Number(parseFloat(noiA).toFixed(2)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('.noi-percentage-'+key).html(noiP.toFixed(2)+'%');

        if(!isNaN(depA)){
            ebitda = parseFloat(noiA)+depA;
            let ebitdaP = ebitda/revAmount[key];

            $('.ebitda-amount-'+key).html(Number(parseFloat(ebitda).toFixed(2)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('.ebitda-percentage-'+key).html(ebitdaP.toFixed(2)+'%');
        }
        else{
            let ebitdaP = ebitda/revAmount[key]*100;

            $('.ebitda-amount-'+key).html(Number(parseFloat(ebitda).toFixed(2)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('.ebitda-percentage-'+key).html(ebitdaP.toFixed(2)+'%');
        }

    });

    Object.keys(otherExpense).forEach(key => {
        let totalExpenseA = opexTotalAmount[key]+otherExpense[key];
        let totalExpenseP = totalExpenseA/revAmount[key]*100;

        $('.total-expense-amount-'+key).html(Number(parseFloat(totalExpenseA).toFixed(2)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('.total-expense-percentage-'+key).html(totalExpenseP.toFixed(2)+'%');
    });

</script>
@endpush
