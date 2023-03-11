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

Route::get('/', function () {
    return view('welcome');
});

/*

Route::get('pleb/welcome_member', function () {

    $user = App\Models\User::find('98842033-952d-46f4-b0cf-6cbc5e42a7e7');
    return new App\Mail\WelcomeMember($user,$user->password_get_info);
});

Route::get('pleb/forgot_password', function () {

    $user = App\Models\User::find('98842033-952d-46f4-b0cf-6cbc5e42a7e7');
    return new App\Mail\RecuperacionContrasenia($user);
});

Route::get('pleb/reject_report', function () {

    $user = App\Models\User::find('98842033-952d-46f4-b0cf-6cbc5e42a7e7');
    return new App\Mail\RejectedReport($motivo, $reporte, $user, $ute);
});

*/

