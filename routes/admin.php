<?php

Route::group(['prefix'  =>  'admin'], function () {

    Route::get('login', 'Admin\LoginController@showLoginForm')->name('admin.login');
   
    Route::post('login', 'Admin\LoginController@login')->name('admin.login.post');
    Route::get('logout', 'Admin\LoginController@logout')->name('admin.logout');
    Route::get('register', 'Admin\RegisterController@showRegisterForm')->name('admin.register');
    Route::post('register', 'Admin\RegisterController@register')->name('admin.register.post');


    Route::group(['middleware' => ['auth:admin']], function () {

        Route::get('/', function () {
            return view('admin.dashboard.index');
        })->name('admin.dashboard');
        Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');
        Route::get('/settings', 'Admin\SettingController@index')->name('admin.settings');
        Route::post('/settings', 'Admin\SettingController@update')->name('admin.settings.update');
        
        Route::group(['prefix'  =>   'categories'], function() {

            Route::get('/', 'Admin\CategoryController@index')->name('admin.categories.index');
            Route::get('/create', 'Admin\CategoryController@create')->name('admin.categories.create');
            Route::post('/store', 'Admin\CategoryController@store')->name('admin.categories.store');
            Route::get('/{category}/edit', 'Admin\CategoryController@edit')->name('admin.categories.edit');
            Route::put('/update', 'Admin\CategoryController@update')->name('admin.categories.update');
            Route::delete('/{category}/delete', 'Admin\CategoryController@delete')->name('admin.categories.delete');
        
        });

        Route::group(['prefix'  =>   'attributes'], function() {

            Route::get('/', 'Admin\AttributeController@index')->name('admin.attributes.index');
            Route::get('/create', 'Admin\AttributeController@create')->name('admin.attributes.create');
            Route::post('/store', 'Admin\AttributeController@store')->name('admin.attributes.store');
            Route::get('/{attribute}/edit', 'Admin\AttributeController@edit')->name('admin.attributes.edit');
            Route::put('/{attribute}/update', 'Admin\AttributeController@update')->name('admin.attributes.update');
            Route::delete('/{attribute}/delete', 'Admin\AttributeController@delete')->name('admin.attributes.delete');
            
            Route::post('/get-values', 'Admin\AttributeValueController@getValues');
            Route::post('/add-values', 'Admin\AttributeValueController@addValues');
            Route::post('/update-values', 'Admin\AttributeValueController@updateValues');
            Route::post('/delete-values', 'Admin\AttributeValueController@deleteValues');
    
    });
    Route::group(['prefix'  =>   'brands'], function() {

        Route::get('/', 'Admin\BrandController@index')->name('admin.brands.index');
        Route::get('/create', 'Admin\BrandController@create')->name('admin.brands.create');
        Route::post('/store', 'Admin\BrandController@store')->name('admin.brands.store');
        Route::get('/{brand}/edit', 'Admin\BrandController@edit')->name('admin.brands.edit');
        Route::put('/{brand}/update', 'Admin\BrandController@update')->name('admin.brands.update');
        Route::delete('/{brand}/delete', 'Admin\BrandController@delete')->name('admin.brands.delete');
    
    });
    Route::group(['prefix' => 'products'], function () {

        Route::get('/', 'Admin\ProductController@index')->name('admin.products.index');
        Route::get('/create', 'Admin\ProductController@create')->name('admin.products.create');
        Route::post('/store', 'Admin\ProductController@store')->name('admin.products.store');
        Route::get('/{product}/edit', 'Admin\ProductController@edit')->name('admin.products.edit');
        Route::put('/{product}/update', 'Admin\ProductController@update')->name('admin.products.update');
        Route::delete('/{product}/delete', 'Admin\ProductController@delete')->name('admin.products.delete');

        Route::post('images/upload', 'Admin\ProductImageController@upload')->name('admin.products.images.upload');
        Route::delete('images/{image}/delete', 'Admin\ProductImageController@delete')->name('admin.products.images.delete');

        Route::get('attributes/load', 'Admin\ProductAttributeController@loadAttributes');
        Route::post('attributes', 'Admin\ProductAttributeController@productAttributes');
        Route::post('attributes/values', 'Admin\ProductAttributeController@loadValues');
        Route::post('attributes/add', 'Admin\ProductAttributeController@addAttribute');
        Route::post('attributes/delete', 'Admin\ProductAttributeController@deleteAttribute');
     });
     
     Route::group(['prefix' => 'orders'], function () {
        Route::get('/', 'Admin\OrderController@index')->name('admin.orders.index');
        Route::get('/{order}/show', 'Admin\OrderController@show')->name('admin.orders.show');
        Route::get('/{order}/edit', 'Admin\OrderController@edit')->name('admin.orders.edit');
        Route::put('/{order}/update', 'Admin\OrderController@update')->name('admin.orders.update');
        Route::delete('/{order}/delete', 'Admin\OrderController@delete')->name('admin.orders.delete');

        Route::get('/trashed', 'Admin\OrderController@trashed');
		Route::get('/restore/{order}', 'Admin\OrderController@restore');
		
		Route::get('/{order}/cancel', 'Admin\OrderController@cancel');
		Route::put('/cancel/{order}', 'Admin\OrderController@doCancel');
		Route::post('/complete/{order}', 'Admin\OrderController@doComplete');
     });
     Route::group(['prefix' => 'shipments'], function () {
        Route::get('/', 'Admin\ShipmentController@index')->name('admin.shipments.index');
        Route::get('/{shipment}/show', 'Admin\ShipmentController@show')->name('admin.shipments.show');
        Route::get('/{shipment}/edit', 'Admin\ShipmentController@edit')->name('admin.shipments.edit');
        Route::put('/{shipment}/update', 'Admin\ShipmentController@update')->name('admin.shipments.update');
      
     });

     Route::get('reports/revenue', 'Admin\ReportController@revenue');
		Route::get('reports/product', 'Admin\ReportController@product');
		Route::get('reports/inventory', 'Admin\ReportController@inventory');
		Route::get('reports/payment', 'Admin\ReportController@payment');
});
});