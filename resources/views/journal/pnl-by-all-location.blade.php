
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
            @foreach ($locations as $key_loc => $loc)

            <table class="table table-bordered" id="pnl-location{{ $key_loc }}">
                <thead style="background-color: #0047ab; color:white;">
                    <tr>
                        <th class="text-center" width="30%">{{ $loc['location_name'] }}</th>
                        @foreach($columnYear as $key => $year)
                            <th class="text-center">{{ $year }}</th>
                            <th>%</th>
                        @endforeach
                    </tr>

                </thead>
                <tbody>
                    @foreach (config('pnl-template.pnl_1') as $key_pnl => $pnl_header)


                        @if (!is_array($pnl_header))
                        @php
                            $pnlHeader = str_replace(' ', '-', strtolower($pnl_header));
                        @endphp
                        <tr>
                            <th>
                                {{ $pnl_header }}
                            </th>
                            @foreach($columnYear as $keyYear => $year)
                                <td class="{{ $pnlHeader }}-amount" data-id="{{ $year }}">0.00</td>
                                <th><span class="{{ $pnlHeader }}-percentage-{{ $year }}">0%</span></th>
                            @endforeach
                        </tr>
                        @else
                            @php
                                $pnlHeader = str_replace(' ', '-', strtolower($key_pnl));
                            @endphp
                            <tr>
                                <th colspan="100%">
                                    {{ $key_pnl }}
                                </th>
                            </tr>
                            @foreach($pnl_header as $row_pnl)
                            <tr>
                                <td>
                                    {{ $row_pnl }}
                                </td>
                                @foreach($columnYear as $keyYear => $year)
                                    <td class="{{ $pnlHeader }}-amount" data-id="{{ $year }}" data-name="{{ $row_pnl }}">0.00</td>
                                    <th><span class="{{ $pnlHeader }}-percentage-{{ $year }}">0%</span></th>
                                @endforeach
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>

            @endforeach

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
