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

use \Cart as Cart;
Route::get('/', 'LandingPageController@index')->name('landing-page');

Route::get('/shop', 'ShopController@index')->name('shop.index');
Route::get('/shop/{product}', 'ShopController@show')->name('shop.show');

// Route::view('/cart', 'cart');
Route::get('/cart', 'CartController@index')->name('cart.index');
Route::post('/cart', 'CartController@store')->name('cart.store');
Route::post('/cart/switchToSaveForLater/{product}', 'CartController@switchToSaveForLater')->name('cart.switchToSaveForLater');
Route::delete('/cart/{rowId}', 'CartController@destroy')->name('cart.destroy');
Route::patch('/cart/{rowId}', 'CartController@update')->name('cart.update');

Route::delete('/saveForLater/{rowId}', 'saveForLaterController@destroy')->name('saveForLater.destroy');
Route::post('/saveForLater/switchToart/{product}', 'saveForLaterController@switchToCart')->name('saveForLater.switchToCart');



//Route::delete('/cart/{rowId}', 'CartController@destroylater')->name('cart.destroylater');

Route::get('empty',function(){

      Cart::instance('saveForLater')->destroy();

});

//we used this middleware to make sure that the user proceding to checkout is a logged user
Route::get('/checkout', 'CheckoutController@index')->name('checkout.index')->middleware('auth');
Route::post('/checkout', 'CheckoutController@store')->name('checkout.store');
Route::get('/thankyou', 'ConfirmationController@index')->name('confirmation.index');
Route::get('/guestCheckout', 'CheckoutController@index')->name('guestCheckout.index');



Route::post('/coupon','CouponsController@store')->name('coupon.store');
Route::delete('/coupon','CouponsController@destroy')->name('coupon.destroy');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/mailable',function(){
    $order = App\Order::find(1);
    return new App\Mail\OrderPlaced($order);
});