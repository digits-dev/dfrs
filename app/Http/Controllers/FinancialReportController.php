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
            ->where('customer_name',$request->company)
            ->get();

        $cogs = DB::table('cogs_by_year')
            ->where('pnldate',$request->year)
            ->where('customer_name',$request->company)
            ->get();

        $opex = DB::table('opex_by_year')
            ->where('pnldate',$request->year)
            ->where('customer_name',$request->company)
            ->orderBy('sequence','ASC')
            ->orderBy('pnldate','ASC')
            ->get();

        $opex_sum = DB::table('opex_by_year')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'customer_name')
            ->where('pnldate',$request->year)
            ->where('customer_name',$request->company)
            ->groupBy('pnldate','customer_name')
            ->get();

        $gross_income = DB::table('final_gross_income_by_year')
            ->where('pnldate',$request->year)
            ->where('customer_name',$request->company)
            ->get();

        $otex = DB::table('otex_by_year')
            ->where('pnldate',$request->year)
            ->where('customer_name',$request->company)
            ->get();

        $data['revenues'] = $revenues;
        $data['cogs']  = $cogs;
        $data['opex']  = $opex;
        $data['opex_sum']  = $opex_sum;
        $data['gross_income'] = $gross_income;
        $data['otex']  = $otex;
        $data['year']  = $request->year;

        return $data;
    }

    public function byCompanyYearMonth(Request $request){
        $data = [];

        $final_cogs = DB::select('call cogs_by_year_month_pivot()');
        $final_revenue = DB::select('call revenue_by_year_month_pivot()');
        $final_otex = DB::select('call otex_by_year_month_pivot()');
        $final_opex = DB::select('call opex_by_year_month_pivot("'.$request->year.'-01","'.$request->year.'-'.$request->month.'")');

        $revenues = DB::table('revenue_by_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->get();

        $cogs = DB::table('cogs_by_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->get();

        $opex = DB::table('opex_by_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->orderBy('sequence','ASC')
            ->orderBy('pnldate','ASC')
            ->get();

        $opex_sum = DB::table('opex_by_year_month')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'customer_name')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->groupBy('pnldate','customer_name')
            ->get();

        $gross_income = DB::table('final_gross_income_by_year_month')
            ->select(DB::raw("pnldate, SUM(gross_income) as gross_income"))
            ->where('customer_name',$request->company)
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->groupBy('pnldate')
            ->orderBy('pnldate','ASC')
            ->get()->toArray();

        $otex = DB::table('otex_by_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->get();

        $otex_sum = DB::table('otex_by_year_month')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'customer_name')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->groupBy('pnldate','customer_name')
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

        $data['gross_income'] = $gross_income;
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

        $final_cogs = DB::select('call cogs_by_location_year_month_pivot("'.$request->location.'")');
        $final_revenue = DB::select('call revenue_by_location_year_month_pivot("'.$request->location.'")');
        $final_opex = DB::select('call opex_by_location_year_month_pivot("'.$request->location.'")');
        $final_otex = DB::select('call otex_by_location_year_month_pivot("'.$request->location.'")');
        // $final_opex = DB::select('call opex_by_year_month_pivot()');

        $revenues = DB::table('revenue_by_location_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->where('location_name',$request->location)
            ->get();

        $cogs = DB::table('cogs_by_location_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->where('location_name',$request->location)
            ->get();

        $opex = DB::table('opex_by_location_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->where('location_name',$request->location)
            ->orderBy('sequence','ASC')
            ->orderBy('pnldate','ASC')
            ->get();

        $opex_sum = DB::table('opex_by_location_year_month')
            ->select('pnldate',DB::raw('sum(amount) as sum_amount'),'customer_name','location_name')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->where('location_name',$request->location)
            ->groupBy('pnldate','customer_name','location_name')
            ->get();

        $gross_income = DB::table('final_gross_income_by_location_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->where('location_name',$request->location)
            ->get();

        $otex = DB::table('otex_by_location_year_month')
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-'.$request->month])
            ->where('customer_name',$request->company)
            ->where('location_name',$request->location)
            ->get();

        $data['columnYear'] = self::generateColumnYear($request->year,$request->month);
        $data['revenues'] = $revenues;
        $data['revenues_data'] = $final_revenue;
        $data['cogs'] = $cogs;
        $data['cogs_data'] = $final_cogs;
        $data['opex'] = $opex;
        $data['opex_data'] = $final_opex;
        $data['opex_sum'] = $opex_sum;
        $data['gross_income'] = $gross_income;
        $data['otex'] = $otex;
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
