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

        $revenues = DB::table('revenue_by_year_month')->whereBetween('pnldate',[$request->year.'-01',$request->year.'-12'])
            ->where('customer_name',$request->company)
            ->get();

        $cogs = DB::table('cogs_by_year_month')->whereBetween('pnldate',[$request->year.'-01',$request->year.'-12'])
            ->where('customer_name',$request->company)
            ->get();

        $opex = DB::table('opex_by_year_month')->whereBetween('pnldate',[$request->year.'-01',$request->year.'-12'])
            ->where('customer_name',$request->company)
            ->orderBy('sequence','ASC')
            ->get();

        $gross_income = DB::table('final_gross_income_by_year_month')
            ->select(DB::raw("pnldate, SUM(gross_income) as gross_income"))
            ->where('customer_name',$request->company)
            ->whereBetween('pnldate',[$request->year.'-01',$request->year.'-12'])
            ->groupBy('pnldate')
            ->orderBy('pnldate','ASC')
            ->get()->toArray();

        $otex = DB::table('otex_by_year_month')->whereBetween('pnldate',[$request->year.'-01',$request->year.'-12'])
            ->where('customer_name',$request->company)
            ->get();

        // $revenue_key = [];
        // foreach ($revenues as $key => $value) {
        //     if(!in_array($value->chart_account_subtype,$revenue_key)){
        //         $revenue_key[$value->chart_account_subtype][$value->pnldate] = [
        //             'pnldate' => $value->pnldate,
        //             'amount' => $value->amount,
        //             'customer_name' => $value->customer_name,
        //         ];
        //     }
        // }

        // $cogs_key = [];
        // foreach ($cogs as $key => $value) {
        //     if(!in_array($value->chart_account_subtype,$cogs_key)){
        //         $cogs_key[$value->chart_account_subtype][$value->pnldate] = [
        //             'pnldate' => $value->pnldate,
        //             'amount' => $value->amount,
        //             'customer_name' => $value->customer_name,
        //         ];
        //     }
        // }

        $opex_key = [];
        foreach ($opex as $key => $value) {
            if(!in_array($value->chart_account_subtype,$opex_key)){
                $opex_key[$value->chart_account_subtype][$value->pnldate] = [
                    'pnldate' => $value->pnldate,
                    'amount' => $value->amount,
                    'customer_name' => $value->customer_name,
                ];
            }
        }

        $otex_key = [];
        foreach ($otex as $key => $value) {
            if(!in_array($value->chart_account_subtype,$otex_key)){
                $otex_key[$value->chart_account_subtype][$value->pnldate] = [
                    'pnldate' => $value->pnldate,
                    'amount' => $value->amount,
                    'customer_name' => $value->customer_name,
                ];
            }
        }
        $data['revenues'] = $revenues;
        $data['revenues_data'] = $final_revenue;
        $data['cogs']  = $cogs;
        $data['cogs_data']  = $final_cogs;
        $data['opex']  = $opex_key;

        $data['otex']  = $otex_key;
        $data['gross_income'] = $gross_income;

        return $data;
    }

    public function byLocationQuarter(Request $request)
    {
        # code...
    }
}
