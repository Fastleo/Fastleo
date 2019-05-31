<?php

Route::group(['prefix' => 'fastleo', 'middleware' => ['web', Fastleo\Fastleo\CheckAuth::class]], function () {

    // Auth
    Route::any('/', 'Fastleo\Fastleo\LoginController@login')->name('fastleo');
    Route::get('/logout', 'Fastleo\Fastleo\LoginController@logout')->name('fastleo.logout');

    // Info
    Route::get('/info', 'Fastleo\Fastleo\InfoController@index')->name('fastleo.info');
    Route::get('/info/clear', 'Fastleo\Fastleo\InfoController@clear')->name('fastleo.info.clear');

    // Filemanager
    Route::get('/filemanager', 'Fastleo\Fastleo\FilemanagerController@index')->name('fastleo.filemanager');
    Route::any('/filemanager/create', 'Fastleo\Fastleo\FilemanagerController@create')->name('fastleo.filemanager.create');
    Route::any('/filemanager/uploads', 'Fastleo\Fastleo\FilemanagerController@uploads')->name('fastleo.filemanager.uploads');

    // Models
    Route::get('/app/{model}', 'Fastleo\Fastleo\ModelController@index');
    Route::any('/app/{model}/add', 'Fastleo\Fastleo\ModelController@add');
    Route::get('/app/{model}/menu_on', 'Fastleo\Fastleo\ModelController@menuOn');
    Route::get('/app/{model}/menu_off', 'Fastleo\Fastleo\ModelController@menuOff');
    Route::get('/app/{model}/menu_add', 'Fastleo\Fastleo\ModelController@menuAdd');
    Route::get('/app/{model}/sorting_fix', 'Fastleo\Fastleo\ModelController@sortingFix');
    Route::get('/app/{model}/sorting_add', 'Fastleo\Fastleo\ModelController@sortingAdd');
    Route::get('/app/{model}/rows_export', 'Fastleo\Fastleo\ModelController@rowsExport');
    Route::any('/app/{model}/rows_import', 'Fastleo\Fastleo\ModelController@rowsImport');
    Route::get('/app/{model}/up/{row_id}', 'Fastleo\Fastleo\ModelController@up')->where('row_id', '[0-9]+');
    Route::get('/app/{model}/down/{row_id}', 'Fastleo\Fastleo\ModelController@down')->where('row_id', '[0-9]+');
    Route::get('/app/{model}/menu/{row_id}', 'Fastleo\Fastleo\ModelController@menu')->where('row_id', '[0-9]+');
    Route::any('/app/{model}/edit/{row_id}', 'Fastleo\Fastleo\ModelController@edit')->where('row_id', '[0-9]+');
    Route::get('/app/{model}/delete/{row_id}', 'Fastleo\Fastleo\ModelController@delete')->where('row_id', '[0-9]+');

});