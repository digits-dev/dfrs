<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminFinancialReportsController;
use App\Http\Controllers\AdminCustomersController;
use App\Http\Controllers\AdminTradingPartnersController;
use App\Http\Controllers\AdminInterCompaniesController;
use App\Http\Controllers\AdminCategoriesController;
use App\Http\Controllers\AdminBrandsController;
use App\Http\Controllers\AdminLocationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('admin/login');
});

Route::group(['prefix'=>'admin'], function(){

    Route::post('financial_reports/journal-upload',[AdminFinancialReportsController::class, 'journalUpload'])->name('fs.upload');
    Route::get('financial_reports/journal',[AdminFinancialReportsController::class, 'journalUploadView'])->name('fs.upload-view');
    Route::get('financial_reports/template',[AdminFinancialReportsController::class, 'uploadTemplate'])->name('fs.template');
    Route::get('financial_reports/journal-report',[AdminFinancialReportsController::class, 'journalReport'])->name('fs.report');
    Route::post('financial_reports/generate-report',[AdminFinancialReportsController::class, 'generateReport'])->name('fs.generate-report');

    //import customer
    Route::post('customers/import-upload',[AdminCustomersController::class, 'customerUpload'])->name('customer.upload');
    Route::get('customers/import',[AdminCustomersController::class, 'customerUploadView'])->name('customer.upload-view');
    Route::get('customers/template',[AdminCustomersController::class, 'uploadTemplate'])->name('customer.template');

    //import trading_partners
    Route::post('trading_partners/import-upload',[AdminTradingPartnersController::class, 'tradingPartnerUpload'])->name('trading-partner.upload');
    Route::get('trading_partners/import',[AdminTradingPartnersController::class, 'tradingPartnerUploadView'])->name('trading-partner.upload-view');
    Route::get('trading_partners/template',[AdminTradingPartnersController::class, 'uploadTemplate'])->name('trading-partner.template');

    //import interco
    Route::post('inter_companies/import-upload',[AdminInterCompaniesController::class, 'intercoUpload'])->name('interco.upload');
    Route::get('inter_companies/import',[AdminInterCompaniesController::class, 'intercoUploadView'])->name('interco.upload-view');
    Route::get('inter_companies/template',[AdminInterCompaniesController::class, 'uploadTemplate'])->name('interco.template');

    //import category
    Route::post('categories/import-upload',[AdminCategoriesController::class, 'categoryUpload'])->name('category.upload');
    Route::get('categories/import',[AdminCategoriesController::class, 'categoryUploadView'])->name('category.upload-view');
    Route::get('categories/template',[AdminCategoriesController::class, 'uploadTemplate'])->name('category.template');

    //import brand
    Route::post('brands/import-upload',[AdminBrandsController::class, 'brandUpload'])->name('brand.upload');
    Route::get('brands/import',[AdminBrandsController::class, 'brandUploadView'])->name('brand.upload-view');
    Route::get('brands/template',[AdminBrandsController::class, 'uploadTemplate'])->name('brand.template');

    //import location
    Route::post('locations/import-upload',[AdminLocationsController::class, 'locationUpload'])->name('location.upload');
    Route::get('locations/import',[AdminLocationsController::class, 'locationUploadView'])->name('location.upload-view');
    Route::get('locations/template',[AdminLocationsController::class, 'uploadTemplate'])->name('location.template');

});
