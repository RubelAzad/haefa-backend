<?php

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


Route::group(['middleware' => ['auth']], function () {
    Route::get('barcodeprint', 'BarcodePrintController@index')->name('barcodeprint');
    Route::get('latest-range/{id}', 'BarcodePrintController@latest_range')->name('latest.range');
    Route::group(['prefix' => 'barcodeprint', 'as'=>'barcodeprint.'], function () {
        Route::post('barcode/product-autocomplete-search', 'BarcodeController@autocomplete_search_product');
    Route::post('barcode/search-product', 'BarcodeController@search_product')->name('barcode.search.product');
    Route::get('print-barcode', 'BarcodeController@index')->name('print.barcode');
    Route::post('generate-barcode', 'BarcodeController@generateBarcode')->name('generate.barcode');
    });
});
