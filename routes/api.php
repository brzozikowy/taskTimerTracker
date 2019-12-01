<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([], function () {
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('languages', ['uses' => 'LanguagesController@getAllLangs']);

    Route::get('tasks', ['uses' => 'TaskController@getAllTasks']);
    Route::post('tasks/create', ['uses' => 'TaskController@create']);
    Route::post('tasks/edit/{id}', ['uses' => 'TaskController@edit']);
    Route::delete('tasks/delete/{id}', ['uses' => 'TaskController@delete']);
    Route::post('tasks/changeStatus/{id}', ['uses' => 'TaskController@changeStatus']);

    Route::post('comment/add/{task_id}', ['uses'=> 'CommentController@addComment']);
    Route::get('timer', ['uses' => 'TaskController@timer']);

    Route::post('addTemplate', ['uses' => 'TemplateController@addTemplate']);
    Route::post('createTemplate', ['uses' => 'TemplateController@createTemplate']);

    Route::get('list/download', ['uses' => 'FilesController@download']);
});
