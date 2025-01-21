<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;

trait permissionTrait {
    public function hasPermission(){

        $permissionList = ['department', 'role', 'permission', 'user', 'leave', 'notice', 'mail'];
//        $permissions = ['can-add', 'can-edit', 'can-delete', 'can-view', 'can-list'];
//        $crudRoutes = ['create', 'store', 'edit', 'index', 'update', 'delete'];

        foreach ($permissionList as $permissionItem){
            if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-add']) && (Route::is($permissionItem.'s.create') || Route::is($permissionItem.'s.store') )){
                return abort(401);
            }

            if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-edit']) && (Route::is($permissionItem.'s.edit') || Route::is($permissionItem.'s.update') )){
                return abort(401);
            }

            if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-list']) && (Route::is($permissionItem.'s.index') || Route::is('accept-reject-leave')) ){
                return abort(401);
            }

            if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-delete']) && Route::is($permissionItem.'s.delete') ){
                return abort(401);
            }
        }

        if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-list']) && (Route::is($permissionItem.'s.edit') || Route::is($permissionItem.'s.update') )){
            return abort(401);
        }


    }
}
