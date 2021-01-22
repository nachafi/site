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

Route::view('/', 'site.pages.homepage');
Route::get('/category/{slug}', 'Site\CategoryController@show')->name('category.show');
Route::get('/category', 'Site\CategoryController@index')->name('category.index');
Route::get('/product/{slug}', 'Site\ProductController@show')->name('product.show');
Route::get('/products', 'Site\ProductController@index')->name('products.index');
Route::post('/products', 'Site\ProductController@search')->name('products.search');
// Like Or Dislike
Route::post('/save-likedislike','Site\ProductController@save_likedislike');


Route::post('/product/add/cart', 'Site\ProductController@addToCart')->name('product.add.cart');
Route::get('/cart', 'Site\CartController@getCart')->name('checkout.cart');
Route::get('/cart/item/{id}/remove', 'Site\CartController@removeItem')->name('checkout.cart.remove');
Route::get('/cart/clear', 'Site\CartController@clearCart')->name('checkout.cart.clear');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/checkout', 'Site\CheckoutController@getCheckout')->name('checkout.index');
    Route::post('/checkout/order', 'Site\CheckoutController@placeOrder')->name('checkout.place.order');
    Route::get('checkout/payment/complete', 'Site\CheckoutController@complete')->name('checkout.payment.complete');

    Route::get('account/orders', 'Site\AccountController@getOrders')->name('account.orders');
    Route::get('account/', 'Site\AccountController@show')->name('account.show');
    Route::get('/account/{user}/edit', 'Site\AccountController@edit')->name('account.edit');
    Route::put('/account/{user}/update', 'Site\AccountController@update')->name('account.update');
    
    Route::post('/comment/store', 'Site\CommentController@store')->name('comment.add');
    Route::post('/reply/store', 'Site\CommentController@replyStore')->name('reply.add');
    Route::get('/comment/{comment}/edit', 'Site\CommentController@edit')->name('comment.edit');
    Route::put('/comment/{comment}/update', 'Site\CommentController@update')->name('comment.update');
    Route::delete('/comment/{comment}/delete', 'Site\CommentController@delete')->name('comment.delete');

    Route::get('/comment/{comment}/addVote', 'Site\CommentController@addVote')->name('comment.addVote');

    Route::get('/comment/{comment}/removeVote', 'Site\CommentController@removeVote')->name('comment.removeVote');

    

Route::post('/review/store', 'Site\ReviewController@store')->name('review.add');
Route::get('/review/{review}/edit', 'Site\ReviewController@edit')->name('review.edit');
Route::put('/review/{review}/update', 'Site\ReviewController@update')->name('review.update');
Route::delete('/review/{review}/delete', 'Site\ReviewController@delete')->name('review.delete');

Route::get('/wishlist', 'Site\WishlistController@index')->name('wishlist.show');
Route::post('/wishlist/store', 'Site\WishlistController@store')->name('wishlist.add');
Route::delete('/wishlist/{wishlist}/delete', 'Site\WishlistController@delete')->name('wishlist.delete');

Route::post('/product/{product}/save', 'Site\ProductController@save')->name('product.save');
Route::post('/dislike/store', 'Site\LikeDislikeController@dislike')->name('dislike.add');
});
Route::get('about/history', 'Site\AboutController@history')->name('about.history');
Route::get('about/buy', 'Site\AboutController@buy')->name('about.buy');
Route::get('about/delivery', 'Site\AboutController@delivery')->name('about.delivery');
Route::get('custumer/help', 'Site\AboutController@help')->name('custumer.help');
Route::get('custumer/money', 'Site\AboutController@money')->name('custumer.money');
Route::get('custumer/terms', 'Site\AboutController@terms')->name('custumer.terms'); 

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}/{email}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset.token');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    

Auth::routes(['verify' => true]);
require 'admin.php';
