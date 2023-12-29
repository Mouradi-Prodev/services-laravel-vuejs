<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Stringable;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get("/Services",function(){
    return view('Services-and-Blogs');
});
Route::get("/Services_{id}",function(){
    return view('Services-and-Blogs');
});

Route::get('/show-service/{id}/{title}/{description}',[MainController::class,'showService']);

Route::post('/api/fetchDataMain',[MainController::class,'fetchData']);

Route::group(['middleware' => ['auth', 'role','CheckSession']], function () {
    // Admin routes
    Route::get('/admin',  [App\Http\Controllers\AdminController::class, 'index'])->name("admin-home");
});
//User routes
Route::group(['middleware' => ['auth','verified','Usercheck','CheckSession']], function () {
    // User routes
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/addservice', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/ViewServices', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/Settings', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/ServiceView_{id}',[App\Http\Controllers\HomeController::class,'index']);
    //Route::get('/addBlogs', [App\Http\Controllers\HomeController::class, 'index']);
   // Route::get('/ViewBlogs', [App\Http\Controllers\HomeController::class, 'index']);
    
    Route::options('/countries', function (Request $request) {
       // $query = $request->query('q');
        $query = $request->string('q')->trim();
        $countries = DB::table('countries')
            ->where('name', 'like', '%'.$query.'%')
            ->get();
    
        return $countries;
    });
    Route::post('/cities', function (Request $request) {
        // $query = $request->query('q');
         $query = $request->string('q')->trim();
         $cities = DB::table('cities')
             ->where('name', 'like', '%'.$query.'%')
             ->get();
     
         return $cities;
     });
     Route::post('/GetCategories', [UserController::class,"GetCategories"]);
     Route::post('/storeService', [UserController::class,"StoreService"]);
     Route::get('/GetUserServices', [UserController::class,"GetUserServices"]);
     Route::delete('/delservice/{id}', [UserController::class,"deleteservice"]);
     Route::post('/editservice/{id}',[UserController::class,"EditService"]);
     Route::post('/uploadImage', [UserController::class,"UploadImage"]);
     Route::get('/getprofile', [UserController::class,"getprofile"]);
     Route::post('/UpdateUsername',[UserController::class,"updateUsername"]);
     Route::post('/change-password',[UserController::class,"changePassword"]);
     Route::post('/GetServiceView',[UserController::class,"getServiceView"]);
   //  Route::post('/api/add-blog-posts',[UserController::class,"addBlogPost"]);
   //  route::get('/api/blogs',[UserController::class,"getBlogs"]);
   //  route::get('/api/blogs/{blogId}',[UserController::class,"getTheBlog"]);
    // route::post('/api/update-blog',[UserController::class,"UpdateTheBlog"]);
   //  route::delete('/api/delete-theblog',[UserController::class,"deleteTheBlog"]);
});












Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');
use Illuminate\Foundation\Auth\EmailVerificationRequest;
 
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
Auth::routes();

