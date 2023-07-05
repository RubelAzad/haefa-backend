<?php
use Illuminate\Support\Facades\Route;
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
    Route::get('patientage', 'ReportController@index')->name('patientage');
    Route::get('datewisedx', 'ReportController@datewisedxindex')->name('datewisedx');
    Route::any('search-by-age', 'ReportController@SearchByAge')->name('search-by-age');
    Route::get('glucosegraph', 'ReportController@glucosegraphindex')->name('glucosegraph');
    Route::post('glucose-graph', 'ReportController@GlucoseGraph')->name('glucose-graph');
    Route::any('date-wise-dx', 'ReportController@SearchByDate')->name('date-wise-dx');
    Route::get('patient-blood-pressure-graph', 'ReportController@PatientBloodPressureGraph')->name('patientbloodpressuregraph');
<<<<<<< HEAD

=======
    Route::get('ajax-patient-blood-pressure', 'ReportController@AjaxPatientBloodPressure')->name('ajaxpatientbloodpressure');
    
    Route::get('heart-rate-graph','ReportController@HeartRateGraph')->name('heartrategraph');
    Route::get('ajax-heart-rate-graph','ReportController@AjaxHeartRateGraph')->name('ajaxheartrategraph');
    
    Route::get('temperature-graph','ReportController@TemperatureGraph')->name('temperaturegraph');
    Route::get('ajax-temperature-graph','ReportController@AjaxTemperatureGraph')->name('ajaxtemperaturegraph');
    
>>>>>>> 92ea96b7c342b076f0925a7bccf1a62aa6e039bb
    Route::group(['prefix' => 'patientage', 'as'=>'patientage.'], function () {
        Route::post('show', 'ReportController@show')->name('show');
    });
});
