<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\CacheController;
use App\Http\Controllers\Api\NotesAndLabelController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\MailController;
use App\Request\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/newregister',[AuthController::class,'newregister'])->name('newregister');
Route::post('/login',[AuthController::class,'login']);

//Route::get('/userInfo',[AuthController::class,'userInfo']);
Route::group(['middleware' => ['auth:api']], function(){
    Route::get('/userInfo',[AuthController::class,'userInfo']);
});
//Route::get('/userInfo',[AuthController::class,'userInfo'])->middleware('auth:api');
Route::post('/updateUser',[AuthController::class,'updateUser']);
Route::post('/deleteUser',[AuthController::class,'deleteUser']);
Route::post('/resetPassword',[PasswordController::class,'resetPassword']);
Route::post('/forgotPassword',[PasswordController::class,'forgotPassword']);
Route::post('/reset/{token}',[PasswordController::class,'reset']);

// Route::get('/resend', [VerificationController::class, 'resend']);
// Route::get('/verify/{id}/{hash}', [VerificationController::class, 'resend']);
Route::post('/addToNotes',[NotesAndLabelController::class,'addToNotes']);
Route::post('/addToLabel',[NotesAndLabelController::class,'addToLabel']);

Route::get('/joinTable',[NotesAndLabelController::class,'joinTable']);
Route::post('/delete',[NotesAndLabelController::class,'delete']);
Route::post('/updateNotes',[NotesAndLabelController::class,'updateNotes']);
Route::post('/updateLabel',[NotesAndLabelController::class,'updateLabel']);

Route::get('/getUser',[CacheController::class, 'getUser']);
Route::get('/getNotesAndLabel',[CacheController::class, 'getNotesAndLabel']);

Route::post('/sendEmail', [Mail::class, 'sendEmail']);