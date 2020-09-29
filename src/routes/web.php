<?php

Route::group(['prefix' => 'fastleo', 'middleware' => ['web']], function () {

    // Auth
    Route::any('/', 'Fastleo\Fastleo\app\Http\Controllers\LoginController@login')->name('fastleo.login');
    Route::get('logout', 'Fastleo\Fastleo\app\Http\Controllers\LoginController@logout')->name('fastleo.logout');

    // Info
    Route::get('info', 'Fastleo\Fastleo\app\Http\Controllers\InfoController@index')->name('fastleo.info');
    Route::get('info/clear', 'Fastleo\Fastleo\app\Http\Controllers\InfoController@clear')->name('fastleo.info.clear');

    // Setting
    Route::any('setting', 'Fastleo\Fastleo\app\Http\Controllers\SettingController@setting')->name('fastleo.setting');

    // Users
    Route::get('users', 'Fastleo\Fastleo\app\Http\Controllers\UserController@users')->name('fastleo.users');
    Route::any('users/add', 'Fastleo\Fastleo\app\Http\Controllers\UserController@add')->name('fastleo.users.add');
    Route::any('users/edit/{user_id}', 'Fastleo\Fastleo\app\Http\Controllers\UserController@edit')
        ->where('user_id', '[0-9]+')
        ->name('fastleo.users.edit');
    Route::get('users/delete/{user_id}', 'Fastleo\Fastleo\app\Http\Controllers\UserController@delete')
        ->where('user_id', '[0-9]+')
        ->name('fastleo.users.delete');

    // FileManager
    Route::any('filemanager', 'Fastleo\Fastleo\app\Http\Controllers\FilemanagerController@index')->name('fastleo.filemanager');
    Route::any('filemanager/trash', 'Fastleo\Fastleo\app\Http\Controllers\FilemanagerController@trash')->name('fastleo.filemanager.trash');
    Route::any('filemanager/preview', 'Fastleo\Fastleo\app\Http\Controllers\FilemanagerController@preview')->name('fastleo.filemanager.preview');
    Route::post('filemanager/create', 'Fastleo\Fastleo\app\Http\Controllers\FilemanagerController@create')->name('fastleo.filemanager.create');
    Route::post('filemanager/uploads', 'Fastleo\Fastleo\app\Http\Controllers\FilemanagerController@uploads')->name('fastleo.filemanager.uploads');

    // Models
    Route::get('app/{model}', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@index')->name('fastleo.model');
    Route::any('app/{model}/add', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@add')->name('fastleo.model.add');
    Route::get('app/{model}/menu_on', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@menuOn')->name('fastleo.model.menu_on');
    Route::get('app/{model}/menu_off', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@menuOff')->name('fastleo.model.menu_off');
    Route::get('app/{model}/sorting_fix', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@sortingFix')->name('fastleo.model.sorting_fix');
    Route::get('app/{model}/rows_export', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@rowsExport')->name('fastleo.model.rows_export');
    Route::any('app/{model}/rows_import', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@rowsImport')->name('fastleo.model.rows_import');
    Route::get('app/{model}/up/{row_id}', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@up')
        ->where('row_id', '[0-9]+')
        ->name('fastleo.model.up');
    Route::get('app/{model}/down/{row_id}', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@down')
        ->where('row_id', '[0-9]+')
        ->name('fastleo.model.down');
    Route::get('app/{model}/menu/{row_id}', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@menu')
        ->where('row_id', '[0-9]+')
        ->name('fastleo.model.menu');
    Route::any('app/{model}/edit/{row_id}', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@edit')
        ->where('row_id', '[0-9]+')
        ->name('fastleo.model.edit');
    Route::get('app/{model}/delete/{row_id}/{die?}', 'Fastleo\Fastleo\app\Http\Controllers\ModelController@delete')
        ->where('row_id', '[0-9]+')
        ->name('fastleo.model.delete');

});