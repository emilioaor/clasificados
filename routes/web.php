<?php

// Usuarios sin autenticar
Route::group(['middleware' => 'noAuthCheck'], function() {
    Route::post('/register', ['uses' => 'DefaultController@register', 'as' => 'index.register']);
    Route::post('/login', ['uses' => 'DefaultController@login', 'as' => 'index.login']);
    Route::get('/passwordReset', ['uses' => 'DefaultController@passwordReset', 'as' => 'index.passwordReset']);
    Route::post('/emailPasswordReset', ['uses' => 'DefaultController@emailPasswordReset', 'as' => 'index.emailPasswordReset']);
    Route::get('/restorePassword/{token}', ['uses' => 'DefaultController@restorePassword', 'as' => 'password.reset']);
    Route::put('/changePassword/{token}', ['uses' => 'DefaultController@changePassword', 'as' => 'index.changePassword']);
});

// Cualquier usuario
Route::get('/', ['uses' => 'DefaultController@index', 'as' => 'index.index']);
Route::get('/publication/search/async', ['uses' => 'PublicationController@search', 'as' => 'index.publication.search']);
Route::get('/publication/search/words', ['uses' => 'PublicationController@searchWords', 'as' => 'index.publication.searchWords']);
Route::get('/publication/{publication}', ['uses' => 'PublicationController@show', 'as' => 'index.publication.show', 'middleware' => 'expiredPublication']);

// Usuarios normales
Route::group(['prefix' => 'user', 'middleware' => 'authCheck'], function() {
    Route::get('/logout', ['uses' => 'DefaultController@logout', 'as' => 'index.logout']);
    // Publicaciones
    Route::group(['middleware' => ['ownerAccess', 'expiredPublication'] ], function() {
        Route::post('/publication/{publication}/uploadImage', ['uses' => 'User\PublicationController@uploadImage', 'as' => 'publication.uploadImage']);
        Route::delete('/publication/deleteImage', ['uses' => 'User\PublicationController@deleteImage', 'as' => 'publication.deleteImage']);
        Route::put('/publication/{publication}/updatePosition', ['uses' => 'User\PublicationController@updatePosition', 'as' => 'publication.updatePosition']);
        Route::post('/publication/{publication}/addComment', ['uses' => 'User\PublicationController@addComment', 'as' => 'publication.addComment']);
        Route::resource('/publication', 'User\PublicationController');
    });

    // Lista de deseos
    Route::get('/whistList', ['uses' => 'User\WhistListController@index', 'as' => 'user.whistList.index']);
    Route::post('/whistList/{publication}', ['uses' => 'User\WhistListController@addPublication', 'as' => 'user.whistList.addPublication']);
    Route::delete('/whistList/{publication}', ['uses' => 'User\WhistListController@removePublication', 'as' => 'user.whistList.removePublication']);

    // Configuracion
    Route::get('/config', ['uses' => 'User\UserController@config', 'as' => 'user.config']);
    Route::put('/configUpdate', ['uses' => 'User\UserController@configUpdate', 'as' => 'user.configUpdate']);

    // Notificaciones
    Route::post('/notifications/markAsRead', ['uses' => 'User\UserController@markRead', 'as' => 'user.notification.markRead']);
});

// Admin
Route::group(['prefix' => 'admin', 'middleware' => ['authCheck', 'adminCheck'] ], function() {
    // Configuracion de cateogorias, subcategorias y opciones
    Route::get('/', ['uses' => 'Admin\AdminController@index', 'as' => 'admin.index']);
    Route::post('/addCategory', ['uses' => 'Admin\AdminController@addCategory', 'as' => 'admin.addCategory']);
    Route::put('/updateCategory/{category}', ['uses' => 'Admin\AdminController@updateCategory', 'as' => 'admin.updateCategory']);
    Route::post('/addSubCategory', ['uses' => 'Admin\AdminController@addSubCategory', 'as' => 'admin.addSubCategory']);
    Route::put('/updateSubCategory/{category}', ['uses' => 'Admin\AdminController@updateSubCategory', 'as' => 'admin.updateSubCategory']);
    Route::post('/addOption', ['uses' => 'Admin\AdminController@addOption', 'as' => 'admin.addOption']);
    Route::put('/updateOption/{option}', ['uses' => 'Admin\AdminController@updateOption', 'as' => 'admin.updateOption']);
});