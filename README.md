<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<hr>
<p align="center"><strong>Employee Management System</strong></p>
<hr><hr>

# Laravel Scaffolding

### Install
- Install ui package: `composer require laravel/ui`
- Install ui:auth: `php artisan ui:auth`

This provides a HomeController, a layout and basic authentication system.

### Do these:

- Configure database name in `.env` file.
- Rename the project as: **Employee** in `.env` file.
- Since the database is `SQLite`, it is by default saved in `database/database.sqlite`.


# Admin Template

## Split files

### Get the admin template
- We are going to use **SB Admin Template**. Download it from https://startbootstrap.com/template/sb-admin.
- Extract the file and save it in `public/template/` directory.

### Setup admin template

- Go to `views/` and create: `views/admin/layouts/`.
- Create files in `layouts/`:
  - navbar.blade.php
  - content.blade.php
  - sidebar.blade.php

- Go to `index.html` in the template and 
extract: `navbar`, `sidebar`, `content` section and 
paste it in those respective files in`layouts/` files.

- Now, go to `navbar.blade.php` and `footer.blade.php` and 
rewrite the new paths for css and js used there.
For example, use asset() method since they are in public
directory: 
  - Earlier: `<script src="js/scripts.js"></script>`
  - Later: `<script src="{{asset('template/js/scripts.js')}}"></script>`
  
### Let's check our template

#### Setup a view page

- Delete everything from `welcome.blade.php`.
- It is already the home route: `'/'`.
- Now, code it as:

```php
// welcome.blade.php
@include('admin/layouts/navbar')
@include('admin/layouts/sidebar')
@include('admin/layouts/content')
@include('admin/layouts/footer')
```

Test it. It should show the whole index page, 
as was in the template.


## Create a master file

- Go to `views/admin/layouts/` and create a new file: 
`master.blade.php` and code it as:

```bladehtml
@include('admin.layouts.navbar')
@include('admin.layouts.sidebar')
<div id="layoutSidenav_content">
    <main>
        @yield('content')   
    </main>
@include('admin.layouts.footer')
```

Here this `@yield('content')` will get the main content
wherever it will be extended, for ex: `@extends('content')`.
Note that, `@yield('content')` is inside a `div` and `main`. This is because
the `div` is opened in the `content.blade.php` but is closed in `footer.blade.php`,
but the `main` is opened and closed in the same file.


### Create a view route

```php
Route::view('/employee', 'admin.create');
```
This is a view route, which doesn't require a controller. 
It directly shows a view file.


### create.blade.php

Create a `create.blade.php` file in `views/admin/`.
This is the file the previous route is redirecting to.

- This file is a copy of `home.blade.php` except that it extends
`@extends('admin.layouts.master')` instead of `@extends('layouts.app')`.
Code it as:

```bladehtml
@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```


### Note:

`admin/layouts/create.blade.php` is a way in which we will code 
other pages. This file can be deleted now as we were just seeing
the way to create a page, **which extends `admin/layouts/master`**.

### Update link of Sidebar

Go to `sidebar.blade.php` and update the 
link of Dashboard Button from `index.html` to `{{url('/')}}`.

## Migration

Create these models and migrations:

- Role
    ```php
    $table->id();
    $table->string('name');
    $table->text('description');
    $table->timestamps();
    ```
- Permission
    ```php
    $table->id();
    $table->unsignedBigInteger('role_id');
    $table->text('name');
    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
    $table->timestamps();
    ```
- Department
    ```php
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
    ```
- Leave
    ```php
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->date('from');
    $table->date('to');
    $table->string('type');
    $table->string('description');
    $table->integer('status')->default(0);
    $table->text('message');
    $table->timestamps();
    ```
- Notice
    ```php
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->date('date');
    $table->string('name');
    $table->timestamps();
    ```
- User (already present)
    ```php
    // user migration
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('address')->nullable();
    $table->string('mobile_number')->nullable();
    $table->integer('department_id');
    $table->integer('role_id');
    $table->string('designation');
    $table->date('start_from');
    $table->string('image');
    ```


## Departments






