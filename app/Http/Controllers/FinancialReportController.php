<?php

namespace App\Http\Controllers;

use App\Models\FinancialReport;
use Illuminate\Http\Request;
use DB;

class FinancialReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FinancialReport  $financialReport
     * @return \Illuminate\Http\Response
     */
    public function show(FinancialReport $financialReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinancialReport  $financialReport
     * @return \Illuminate\Http\Response
     */
    public function edit(FinancialReport $financialReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinancialReport  $financialReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FinancialReport $financialReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinancialReport  $financialReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinancialReport $financialReport)
    {
        //
    }

    public function byCompanyYear(Request $request)
    {
        $data = [];

        $revenues = DB::table('revenue_by_year')
            ->where('pnldate',$request->year)
            ->where('inter_company_name',$request->company)
            ->get();

        $cogs = DB::table('cogs_by_year')
            ->where('pnldate',$request->year)
            ->where('inter_company_name',$request->company)
            ->get();

        $opex = DB::table('opex_by_year')
            ->where('pnldate',$request->year)
            ->where('inter_company_name',$request->company)
            ->orderBy('sequence','ASC')
            ->orderBy('pnldate','ASC')
            ->get();

        $opex_sum = DB::table('opex_by_year')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'inter_company_name')
            ->where('pnldate',$request->year)
            ->where('inter_company_name',$request->company)
            ->groupBy('pnldate','inter_company_name')
            ->get();

        $otex = DB::table('otex_by_year')
            ->where('pnldate',$request->year)
            ->where('inter_company_name',$request->company)
            ->get();

        $data['revenues'] = $revenues;
        $data['cogs']  = $cogs;
        $data['opex']  = $opex;
        $data['opex_sum']  = $opex_sum;
        $data['otex']  = $otex;
        $data['year']  = $request->year;
        $data['company']  = $request->company;

        return $data;
    }

    public function byCompanyYearMonth(Request $request){
        $data = [];

        $datefrom = $request->year.'-01';
        $dateto = $request->year.'-'.str_pad($request->month,2,"0",STR_PAD_LEFT);
        $pivotParameter = '"'.$request->company.'","'.$request->year.'-01","'.$request->year.'-'.str_pad($request->month,2,"0",STR_PAD_LEFT).'"';

        $final_cogs = DB::select('call cogs_by_year_month_pivot('.$pivotParameter.')');
        $final_revenue = DB::select('call revenue_by_year_month_pivot('.$pivotParameter.')');
        $final_otex = DB::select('call otex_by_year_month_pivot('.$pivotParameter.')');
        $final_opex = DB::select('call opex_by_year_month_pivot('.$pivotParameter.')');

        $revenues = DB::table('revenue_by_year_month')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->get();

        $cogs = DB::table('cogs_by_year_month')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->get();

        $opex = DB::table('opex_by_year_month')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->orderBy('sequence','ASC')
            ->orderBy('pnldate','ASC')
            ->get();

        $opex_sum = DB::table('opex_by_year_month')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'inter_company_name')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->groupBy('pnldate','inter_company_name')
            ->get();

        $otex = DB::table('otex_by_year_month')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->get();

        $otex_sum = DB::table('otex_by_year_month')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'inter_company_name')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->groupBy('pnldate','inter_company_name')
            ->get();

        $data['columnYear'] = self::generateColumnYear($request->year,$request->month);
        $data['revenues'] = $revenues;
        $data['revenues_data'] = $final_revenue;
        $data['cogs']  = $cogs;
        $data['cogs_data']  = $final_cogs;
        $data['opex']  = $opex;
        $data['opex_data']  = $final_opex;
        $data['opex_sum']  = $opex_sum;
        $data['otex']  = $otex;
        $data['otex_sum']  = $otex_sum;
        $data['otex_data'] = $final_otex;

        $data['year']  = $request->year;
        $data['company']  = $request->company;

        return $data;
    }

    public function byLocationQuarter(Request $request)
    {
        # code...
    }

    public function byLocationYear(Request $request)
    {

    }

    public function byLocationYearMonth(Request $request)
    {
        $data = [];

        $pivotParameter = '"'.$request->company.'","'.$request->location.'","'.$request->year.'-01","'.$request->year.'-'.str_pad($request->month,2,"0",STR_PAD_LEFT).'"';
        $datefrom = $request->year.'-01';
        $dateto = $request->year.'-'.str_pad($request->month,2,"0",STR_PAD_LEFT);

        $final_cogs = DB::select('call cogs_by_location_year_month_pivot('.$pivotParameter.')');
        $final_revenue = DB::select('call revenue_by_location_year_month_pivot('.$pivotParameter.')');
        $final_opex = DB::select('call opex_by_location_year_month_pivot('.$pivotParameter.')');
        $final_otex = DB::select('call otex_by_location_year_month_pivot('.$pivotParameter.')');

        $revenues = DB::table('revenue_by_location_year_month')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->where('location_name',$request->location)
            ->get();

        $cogs = DB::table('cogs_by_location_year_month')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->where('location_name',$request->location)
            ->get();

        $opex = DB::table('opex_by_location_year_month')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->where('location_name',$request->location)
            ->orderBy('sequence','ASC')
            ->orderBy('pnldate','ASC')
            ->get();

        $opex_sum = DB::table('opex_by_location_year_month')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'inter_company_name','location_name')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->where('location_name',$request->location)
            ->groupBy('pnldate','inter_company_name','location_name')
            ->get();

        $otex = DB::table('otex_by_location_year_month')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->where('location_name',$request->location)
            ->get();

        $otex_sum = DB::table('otex_by_location_year_month')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'inter_company_name')
            ->whereBetween('pnldate',[$datefrom,$dateto])
            ->where('inter_company_name',$request->company)
            ->where('location_name',$request->location)
            ->groupBy('pnldate','inter_company_name')
            ->get();

        $data['columnYear'] = self::generateColumnYear($request->year,$request->month);
        $data['revenues'] = $revenues;
        $data['revenues_data'] = $final_revenue;
        $data['cogs'] = $cogs;
        $data['cogs_data'] = $final_cogs;
        $data['opex'] = $opex;
        $data['opex_data'] = $final_opex;
        $data['opex_sum'] = $opex_sum;
        $data['otex'] = $otex;
        $data['otex_sum'] = $otex_sum;
        $data['otex_data'] = $final_otex;
        $data['year'] = $request->year;
        $data['location'] = $request->location;

        return $data;
    }

    public function generateColumnYear($year,$month)
    {
        $columnYear = [];
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];

        foreach ($months as $key => $value) {
            if(intval($month) >= $value){
                array_push($columnYear, $year.'-'.str_pad($value,2,"0",STR_PAD_LEFT));
            }
        }

        return $columnYear;
    }
}
