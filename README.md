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


### create.blade.php - A test file

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

# Migrations

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


# Departments

## Configuration for departments

### Create Department Controller

`php artisan make:controller DepartmentController -r`

### Unguard all the columns from Department Model

```php
class Department extends Model
{
    protected $guarded = [];
}
```

### Create a resource route for departments

```php
Route::resource('departments', App\Http\Controllers\DepartmentController::class);
```


## Create Department Form

### @create method in DepartmentController
This method just returns the view page of the form.

```php
    public function create()
    {
        return view('admin.department.create');
    }
```

### Create view page

- Create `views/admin/department/create.blade.php`.
- Copy everything from `home.blade.php`.
- Change the `@extends` to `@extends('admin.layouts.master')`
- Create a form in `card-body div` and submitting the form 
hits the store method of departments.
- Also, add elements that highlight the input box when
validation errors occur and also a `<span>` to show them.
  (Copy it from `login.blade.php`).
- Also, create a div above the card and form, to show the
Session message of success when the form is successfully submitted.

```bladehtml
<!--views/admin/department/create.blade.php-->
@extends('admin.layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(Session::has('message'))
            <div class="alert alert-success">
                {{Session::get('message')}}
            </div>
            @endif

            <div class="card">
                <div class="card-header">{{ __('Create Department') }}</div>

                <div class="card-body">

                    <form method="post" action="{{route('departments.store')}}"> @csrf

                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{old('name')}}">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description">{{old('description')}}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group text-center mt-2">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


```


## Create Departments

### Reconfigure the Side bar

- Go to the `views/admin/layouts/sidebar.blade.php`.
- Change `Layout` to `Departments`.
- It will contain sub-links as:
  - Create
  - View

### @store method of Department Controller

Steps-
- Validation
- Store in the database

```php
// DepartmentController@store
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:departments',
        ]);
        $department = new Department();
        $department->name = $request->name;
        $department->description = $request->description;
        $department->save();
        return redirect()->back()->with('message','Department added successfully');
    }
```

## List all the departments

### DepartmentController@index method

```php

```

### @index.blade.php

- Create the file: `resources/views/admin/department/index.blade.php`
- Copy everything from `create.blade.php`. Remove all the contents
inside `<div class="col-md-8">`
- Go to `public/template/tables.html` and copy the `<table>` div 
along with its parent `<card>` tag.
- Change the heading to `All Departments`.
- Keep the Heading and only one row as we are going to iterate
the data from our database.
- Keep the columns:
  - SN
  - Name
  - Description
  - Edit
  - Delete

```bladehtml
<!--resources/views/admin/department/index.blade.php-->

@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{Session::get('message')}}
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        All Departments
                    </div>
                    <div class="card-body">

                        @if(count($departments)>0)

                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Edit</th>
                                        <th>Delete</th>

                                    </tr>
                                </thead>

                                <tbody>

                                @foreach($departments as $key=>$department)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$department->name}}</td>
                                    <td>{{$department->description}}</td>
                                    <td>
                                        <a href="{{route('departments.edit', $department->id)}}">
                                            <div class="p-1">
                                            <i class="fas fa-edit"></i>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fas fa-trash"></i>
                                    </td>

                                </tr>
                                @endforeach

                                </tbody>

                            </table>

                        @else
                            No departments found!
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

```

## Update department

### Create button in index page

- In the index page, link the edit button to `department.edit` route.

### DepartmentController@edit method

- Find the department from db using `$id`.
- Return the `views/admin/department/edit.blade.php` file, along
with the found department.

```php
// DepartmentController@edit
    public function edit(string $id)
    {
        $department = Department::find($id);
        return view('admin.department.edit', compact('department')) ;
    }
```

### Create the view file for edit

- Create `views/admin/department/edit.blade.php`.
- Copy everything from create.blade.php.
- Change the Heading to 'Update Department'.
- Change the `action` attribute of the form to: `{{route('admin.department.update', $department->id)}}`.
- In the `<form>` tag, keep the method to `post`. Just after the 
form tag, user blade template for put method: `@method('PUT')`.
- Change all the values of the input file. The values should be
got from the `$department` variable returned by controller.
- Change the submit button name to `Update`.

```bladehtml
<!--views/admin/department/edit.blade.php-->

@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{Session::get('message')}}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">{{ __('Update Department') }}</div>

                    <div class="card-body">

                        <form method="post" action="{{route('departments.update', $department->id)}}">
                        @csrf
                        @method('PATCH')
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{$department->name}}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description">{{$department->description}}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group text-center mt-2">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```


### DepartmentController@update

- Validate-
  - `'name'=>'required|unique:departments,name,'.$id,` ***IMPORTANT
  - It should be unique except the same column.
- Get the instance of `Department` model.
- Update the db.
- Save it.
- Return redirect back to with a success message.

```php
// DepartmentController@update
    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'name'=>'required|unique:departments,name,'.$id,
        ]);

        $department = Department::find($id);
        $department->name = $request->input('name');
        $department->description = $request->input('description');
        $department->save();
        return redirect(route('departments.index'))->with('message','Department updated successfully');
    }

//        Another way of doing it   //////////////////////////
//        $department = Department::find($id);
//        $data = $request->all();
//        $department->update($data);
//        return redirect(route('departments.index'))->with('message','Department updated successfully');
```


## Delete a Department

### Link the Delete Button in Index page

- In the index page, wrap the delete button with a `<a>` tag
which hits `DepartmentController@destroy`.

- Use a Bootstrap 5 Modal so show a Pop-up when the delete
button is pressed.

  - Go to Bootstrap 5 official website and find a modal.
  - There are two sections in it. First, a button with some attributes.
  Second, a code snippet for Modal.
  - Copy the two attributes from `<button>` 
  tag: `data-bs-toggle="modal" data-bs-target="#exampleModal"` and 
  paste them in the `<a>` tag in our index page.
  - Copy the whole Modal Snippet and paste them just after
  the `<a>` tag in our index page.
  - Modify the Modal:
    - change the button names to: `Close` and `Delete`,
    - change the button class to `btn-danger`,
    - change the main text to: `Do you really want to delete?`,
    - change the `data-bs-target="#exampleModal` to 
    `data-bs-target="#exampleModal{{$department->id}}`; do the same
    in Modal `<div id="">` as well: `<div class="modal fade" id="exampleModal{{$department->id}}" ...`.
  - Now, whenever the delete icon is be pressed,
  it shows a pop-up.

- Embed a form in the model which hits the destroy method of 
DepartmentController.

Code snippet of delete button

```bladehtml
<!--Code snippet of delete button-->
<td>
    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$department->id}}">
        <div class="p-1">
            <i class="fas fa-trash"></i>
        </div>
    </a>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal{{$department->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{route('departments.destroy', $department->id)}}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Do you really want to delete?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal End -->
</td>
```


### DepartmentController@destroy

- Find the particular record using `Department` model and then
delete it.
- Return to Index page along with a success message.

```php
// DepartmentController@destroy
    public function destroy(string $id)
    {
        $department = Department::find($id);
        $department->delete();
        return redirect(route('departments.index'))->with('message','Department deleted successfully');
    }
```


[//]: # (Department Ends Here ---------)
<hr>

# Roles

## Configurations

### Unguard all the columns in the Model

```php
// Role Model
class Role extends Model
{
    protected $guarded = [];
}
```

### Create RoleController

`php artisan make:controller RoleController -r`

### Create resource route for roles

```php
Route::resource('roles', RoleController::class);
```

## Create Roles

### Configure the Sidebar for the roles



### RoleController@create

Return the `admin/role/create.blade.php`.

```php
// admin/role/create.blade.php
    public function create()
    {
        return view('admin.role.create');
    }
```

### role\create.blade.php

- Create the file `resources/views/admin/role/create.blade.php`.
- Copy everything from `admin/department/create.blade.php`.
- Change the required things. That's all.

### RoleController@store

```php
// RoleController@store
    public function store(Request $request)
    {
//        return 'store hit';
        $this->validate($request, [
            'name' => 'required|unique:roles',
        ]);

        Role::create($request->all());
        return redirect()->back()->with('message', 'Role created successfully.');
    }
```


## Get all the roles

### RoleController@index

- Get all the roles.
- Return the index view page.

```php
    public function index()
    {
        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }
```

### role\index.blade.php

- Create the file `resources/views/admin/role/index.blade.php`.
- Copy everything from `admin/department/index.blade.php`.
- Change the required things. That's all.


## Update a role

### RoleController@edit

```php
// RoleController@edit
    public function edit(string $id)
    {
        $role = Role::find($id);
        return view('admin.role.edit', compact('role'));
    }
```

### role\edit.blade.php

- Create this file.
- Copy everything from `department/edit.blade.php`.
- Change the required things from `departments` to `roles`.

### RoleController@update

- Validate.
- Find the required resource from db using Model.
- Update all at once: `$role->update($request->all());`.
- Return redirect back with a success message.

```php
// RoleController@update
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,'.$id,
            'description' => 'required',
        ]);

        $role = Role::find($id);
        $role->update($request->all());
        return redirect(route('roles.index'))->with('message', 'Role updated successfully.');
    }
```

### Delete a Role

- Since, index page is already set up with the delete button,
its route and the pop-up.
- So just create the Controller method for it.

```php
// RoleController@destroy

    public function destroy(string $id)
    {
        $role = Role::find($id);
        $role->delete();
        return redirect()->back()->with('message', 'Role deleted successfully.');
    }
```

[//]: # (Roles completed.)

