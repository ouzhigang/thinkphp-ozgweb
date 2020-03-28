<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::group('mgr', function() {
    $namespace_mgr = '\app\mgr\controller';
    
    Route::get('index/getvcode', $namespace_mgr . '\Index@getvcode');
    Route::post('index/login', $namespace_mgr . '\Index@login');

    Route::get('other/logout', $namespace_mgr . '\Other@logout');

    Route::get('art_single/get', $namespace_mgr . '\ArtSingle@get');
    Route::post('art_single/update', $namespace_mgr . '\ArtSingle@update');

    Route::get('user/show', $namespace_mgr . '\User@show');
    Route::post('user/add', $namespace_mgr . '\User@add');
    Route::get('user/del', $namespace_mgr . '\User@del');
    Route::post('user/updatepwd', $namespace_mgr . '\User@updatepwd');

    Route::get('data_cat/show', $namespace_mgr . '\DataCat@show');
    Route::get('data_cat/get', $namespace_mgr . '\DataCat@get');
    Route::get('data_cat/gettree', $namespace_mgr . '\DataCat@gettree');
    Route::post('data_cat/add', $namespace_mgr . '\DataCat@add');
    Route::get('data_cat/del', $namespace_mgr . '\DataCat@del');
    
    Route::get('data/show', $namespace_mgr . '\DataCat@show');
    Route::post('data/add', $namespace_mgr . '\DataCat@add');
    Route::get('data/get', $namespace_mgr . '\DataCat@get');
    Route::get('data/del', $namespace_mgr . '\DataCat@del');
    Route::post('data/upload', $namespace_mgr . '\DataCat@upload');
})
->prefix('/server/mgr/');
