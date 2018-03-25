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

Route::get('/', 'Auth\LoginController@showLoginForm');
Route::post('/forgot-password', 'UsersController@forgot_password')->middleware(['web', 'guest']);
Route::match(['get', 'post'], '/reset/{id}', 'UsersController@reset_password')->middleware(['web', 'guest']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/myteam', 'UsersController@team_roster');
    Route::get('/select/team/member/{uid}', 'UsersController@select_team_member');
    Route::get('/load/projects', 'ProjectsController@load_projects');
    Route::get('/load/member/form', 'ProjectsController@load_member_list');
    Route::get('/load/members', 'UsersController@load_members');
    Route::get('/load/comments/{mid}', 'ProjectsController@load_comments');
    Route::get('/load/dashboard', 'HomeController@load_dashboard');
    Route::get('/load/project/details/{id}', 'ProjectsController@load_project_details');
    Route::get('/load/project/milestones/{id}', 'ProjectsController@load_project_milestones');
    Route::get('/check-first-login', 'UsersController@check_first_login');

    Route::get('/projects', 'ProjectsController@index');
    Route::get('/project/types', 'HomeController@project_types');
    Route::get('/project/edit/{id}/{form?}', 'ProjectsController@edit');
    // Route::get('/project/type/{id}/edit', 'HomeController@edit_project_type');
    Route::get('/project/type/{id}/delete', 'HomeController@delete_project_type');
    Route::get('/user/roles', 'HomeController@user_roles');
    Route::get('/user/delete/{uid}', 'UsersController@delete_user');
    // Route::get('/user/role/{id}/edit', 'HomeController@edit_user_role');
    Route::get('/user/role/{id}/delete', 'HomeController@delete_user_role');
    Route::get('/user/profile', 'UsersController@user_profile');
    Route::get('/comments/{pid}', 'CommentsController@index');
    Route::get('/refresh/dashboard', 'RefreshDashboardController@index');

    Route::post('/search', 'ProjectsController@search');
    Route::post('/search/summary', 'HomeController@search');
    Route::post('/user/update/{uid}', 'UsersController@update_user');
    Route::post('/comment/add', 'ProjectsController@comment_add');
    Route::post('/upload', 'ProjectsController@upload');
    Route::post('/save/user/role', 'HomeController@save_user_role');
    Route::post('/save/project/type', 'HomeController@save_project_type');
    // Route::post('/user/role/{id}/edit', 'HomeController@edit_user_role');
    Route::post('/save/project/type', 'HomeController@save_project_type');
    // Route::post('/project/milestones/update', 'ProjectsController@update_milestones');

    Route::put('/user/profile', 'UsersController@user_profile');
    Route::post('/delete/project', 'ProjectsController@delete_project');
    Route::post('/reply/comment', 'ProjectsController@reply_to_comment');
    // Route::put('/project/type/{id}/edit', 'HomeController@edit_project_type');

    Route::resource('project', 'ProjectsController');
    Route::resource('user', 'UsersController');

    Route::match(['get', 'put'], '/project/type/{id}/edit', 'HomeController@edit_project_type');
    Route::match(['get', 'put'], '/user/role/{id}/edit', 'HomeController@edit_user_role');

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
});
