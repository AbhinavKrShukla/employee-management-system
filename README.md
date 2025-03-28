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
- Rename the project as: **LaravelEMS** in `.env` file.
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

# Models

## Relationships

- `User` has one `Department`.
- `User` has on `Role`.
- `Permission` belongs to `Role`.
- `Role` has one `Permission`.
- `Leave` belongs to `User`.

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

### Configure the Sidebar for the roles

This should be the structure of the sidebar.
- Sidebar
  - Departments
  - Users
    - Roles
      - Create Role
      - View Role

 
## Create Roles

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
- So create the Controller method for it.

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
<hr>

# Employee

## Let's Create Users first

### Create UserController

`php artisan make:controller UserController -r`

### Define $fillables in User model

```php
// User model
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'mobile_number',
        'department_id',
        'role_id',
        'designation',
        'start_from',
        'image',
    ];
```

## Create resource route for Users

```php
Route::resource('users', UserController::class);
```


## Employee Form and Validation

### UserController@create

It returns the view page for form

```php
// UserController@create
    public function create()
    {
        return view('admin.user.create');
    }
```

### user/create.blade.php

- Copy everything from `department/create.blade.php`.
- Change the heading and others.
- Add more required input fields.
- In the department and role, use `<select>` and directly iterate
using respective models and show them.

```bladehtml
<!--user/create.blade.php-->
@extends('admin.layouts.master')

@section('content')
<div class="container mt-5 mb-2">
    <form method="post" action="{{route('users.store')}}" enctype="multipart/form-data"> @csrf
        <div class="row justify-content-center">

            @if(Session::has('message'))
            <div class="alert alert-success" style="width: 90%">
                {{Session::get('message')}}
            </div>
            @endif

            <div class="mb-3" style="width: 80%; font-size: 25px; font-weight: bold">
                Create User
            </div>

            <div class="col-md-6">

                <div class="card">
                    <div class="card-header fw-bold">{{ __('General Information') }}</div>

                    <div class="card-body">

                        <div class="form-group">
                            <label>First Name</label>
                            <input class="form-control @error('first-name') is-invalid @enderror" type="text"
                                   name="first-name"
                                   value="{{old('first-name')}}">
                            @error('first-name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input class="form-control @error('last-name') is-invalid @enderror" type="text"
                                   name="last-name"
                                   value="{{old('last-name')}}">
                            @error('last-name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control @error('address') is-invalid @enderror" type="text"
                                   name="address"
                                   value="{{old('address')}}">
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input class="form-control @error('mobile_number') is-invalid @enderror" type="text"
                                   name="mobile_number"
                                   value="{{old('mobile_number')}}">
                            @error('mobile_number')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Department</label>
                            <div class="w-25">
                                <select class="form-control" name="department_id">
                                    <option value="">Select</option>
                                    @foreach(\App\Models\Department::all() as $department)
                                    <option value="{{$department->id}}"
                                            {{$department->id == old('department_id') ? "selected" : ""}}>
                                        {{$department->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('department_id')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Designation</label>
                            <input class="form-control @error('designation') is-invalid @enderror" type="text"
                                   name="designation"
                                   value="{{old('designation')}}">
                            @error('designation')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Start Date</label>
                            <input class="form-control @error('start_from') is-invalid @enderror" type="date"
                                   name="start_from"
                                   value="{{old('start_from')}}">
                            @error('start_from')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input class="form-control @error('image') is-invalid @enderror" type="file"
                                   name="image"
                                   value="{{old('image')}}" accept="image/*">
                            @error('image')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{--            ----------------------------------------------        --}}

            <div class="col-md-4">

                <div class="card">
                    <div class="card-header fw-bold">{{ __('Login Information') }}</div>

                    <div class="card-body">

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="text"
                                   name="email"
                                   value="{{old('email')}}">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password"
                                   name="password" value="{{old('password')}}">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <div class="w-25">
                                <select class="form-control" name="role_id">
                                    <option value="#">Select</option>
                                    @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{$role->id}}"
                                            @if($role->id == old('role_id')) selected @endif>
                                        {{$role->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('role_id')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                    </div>


                </div>
                <div class="form-group text-center mt-2">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>


        </div>
    </form>
</div>
@endsection

```

### UserController@store

- Validate all the fields. Image should not be required as there is a default option.
- Check if there is an image.
- HashName the image name and encrypt the password.
- Save it to db.
- Redirect back with a success message.

```php
// UserController@store
    public function store(Request $request)
    {
        $this->validate($request,[
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'address' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:12|min:5|unique:users',
            'department_id' => 'required|string|max:255',
            'role_id' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'start_from' => 'required|date',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        if($request->hasFile('image')){
            $image = $request->image->hashName();
            $request->image->move(public_path('profile'), $image);
        } else {
            $image = 'default.png';
        }
        $data['name'] = $request['first-name'].' '.$request['last-name'];
        $data['image'] = $image;
        $data['password'] = bcrypt($request['password']);

        $user = User::create($data);

        return redirect()->back()->with('message', 'User Added Successfully ');
    }
```


## Get all the Employees

### UserController@index

- This method gets all the users from db and returns the
view page `user/index.blade.php`.
```php
// UserController@index
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }
```

### user/index.blade.php

```bladehtml
<!--user/index.blade.php-->
@extends('admin.layouts.master')

@section('content')
<div class="container mt-3">
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
                    All Employees
                </div>
                <div class="card-body">

                    @if(count($users)>0)

                    <table id="datatablesSimple">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Start Date</th>
                            <th>Address</th>
                            <th>Mobile</th>
                            <th>Edit</th>
                            <th>Delete</th>

                        </tr>
                        </thead>

                        <tbody>

                        @foreach($users as $key=>$user)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                <img src="{{url(asset('profile/'.$user->image))}}" alt="Profile"
                                     height="100">
                            </td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->role->name}}</td>
                            <td><span class="badge bg-success">{{$user->department->name}}</span></td>
                            <td>{{$user->designation}}</td>
                            <td>{{$user['start_from']}}</td>
                            <td class="overflow-clip">{{$user->address}}</td>
                            <td>{{$user->mobile_number}}</td>

                            <td>
                                <a href="{{route('users.edit', $user->id)}}">
                                    <div class="p-1">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal"
                                   data-bs-target="#exampleModal{{$user->id}}">
                                    <div class="p-1">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </a>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal{{$user->id}}" tabindex="-1"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{route('users.destroy', $user->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirm
                                                        Delete</h5>
                                                    <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Do you really want to delete?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Modal End -->
                            </td>
                        </tr>
                        @endforeach

                        </tbody>

                    </table>

                    @else
                    No employee found!
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

```

## Edit the form

### UserController@edit

- Find the user using `User` Model.
- Return the view page.

```php
// UserController@edit
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }
```

### user/edit.blade.php

```bladehtml
<!--user/edit.blade.php-->
@extends('admin.layouts.master')

@section('content')
<div class="container mt-5 mb-2">
    <form method="post" action="{{route('users.update', $user->id)}}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row justify-content-center">

            @if(Session::has('message'))
            <div class="alert alert-success" style="width: 90%">
                {{Session::get('message')}}
            </div>
            @endif

            <div class="mb-3" style="width: 80%; font-size: 25px; font-weight: bold">
                Update User
            </div>

            <div class="col-md-6">

                <div class="card">
                    <div class="card-header fw-bold">{{ __('General Information') }}</div>

                    <div class="card-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text"
                                   name="name"
                                   value="{{$user->name}}">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control @error('address') is-invalid @enderror" type="text"
                                   name="address"
                                   value="{{$user->address}}">
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input class="form-control @error('mobile_number') is-invalid @enderror" type="text"
                                   name="mobile_number"
                                   value="{{$user->mobile_number}}">
                            @error('mobile_number')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Department</label>
                            <div class="w-25">
                                <select class="form-control" name="department_id">
                                    <option value="">Select</option>
                                    @foreach(\App\Models\Department::all() as $department)
                                    <option value="{{$department->id}}"
                                            @if($department->id == $user->department_id) selected @endif>
                                        {{$department->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('department_id')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Designation</label>
                            <input class="form-control @error('designation') is-invalid @enderror" type="text"
                                   name="designation"
                                   value="{{$user->designation}}">
                            @error('designation')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Start Date</label>
                            <input class="form-control @error('start_from') is-invalid @enderror" type="date"
                                   name="start_from"
                                   value="{{$user->start_from}}">
                            @error('start_from')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input class="form-control @error('image') is-invalid @enderror" type="file"
                                   name="image"
                                   accept="image/*">
                            @error('image')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{--            ----------------------------------------------        --}}

            <div class="col-md-4">

                <div class="card">
                    <div class="card-header fw-bold">{{ __('Login Information') }}</div>

                    <div class="card-body">

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="text"
                                   name="email"
                                   value="{{$user->email}}">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password"
                                   name="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <div class="w-25">
                                <select class="form-control" name="role_id">
                                    <option value="#">Select</option>
                                    @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{$role->id}}"
                                            @if($role->id == $user->role_id) selected @endif>
                                        {{$role->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('role_id')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                    </div>


                </div>
                <div class="form-group text-center mt-2">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>


        </div>
    </form>
</div>
@endsection

```

### UserController@update

```php
// UserController@update
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'address' => 'required|string|max:255',
            'mobile_number' => 'required|max:12|min:5|unique:users,mobile_number,'.$id,
            'department_id' => 'required|string|max:255',
            'role_id' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'start_from' => 'required|date',
        ]);

        if($request->password){
            $this->validate($request,[
                'password' => 'string|min:6',
            ]);
            $password = bcrypt($request['password']);
        } else {
            $password = $user->password;
        }

        if ($request->hasFile('image')) {
            $this->validate($request,[
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Unlink the previous image, only if it's not default image
            if($user->image != 'default.png'){
                unlink(public_path('profile').$user->image);
                return $user->image;
            }

            // Store the new image
            $image = $request->image->hashName();
            $request->image->move(public_path('profile'), $image);
        } else {
            $image = $user->image;
        }

        $data = $request->all();
        $data['name'] = $request['name'];
        $data['image'] = $image;
        $data['password'] = $password;

        $user->update($data);

        return redirect()->back()->with('message', 'User Updated Successfully ');

    }
```

### UserController@destroy

```php
// UserController@destroy
    public function destroy(string $id)
    {
        $user = User::find($id);

        // Check if user has default image, don't delete it from public path

        if($user->image != 'default.png'){
            unlink(public_path('profile/').$user->image);
        }
        $user->delete();
        return redirect()->back()->with('message', 'User Deleted Successfully ');
    }
```

[//]: # (Employee Completed)
<hr>

# Permissions 

## Create Employee Login

- Make sure that the default login provided by vue 
is working properly. That's all.

### Route Modification

- Enclose the admin routes within the auth middleware.
- Even the `'/'` home page is under middleware.
- Then login.

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::resource('departments', DepartmentController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
```

### Modify the `RouteServiceProvider.php`

- Check if there is `Providers/RouteServiceProvider.php` file.
- If it is, then modify it such that `'/'` is the home page. Else
ignore.

  
### Setup the logout button in Navbar

- The default logout button provided by `bootstrap template` is not functional.
- To make it functional, copy the `<a>` and `<form>` tag of `logout` from 
`resources/views/layouts/app.blade.php` to the navbar: 
`resources/views/admin/layouts/navbar.blade.php`. 
- That's all.

```bladehtml
<!--What to copy and paste-->
<a class="dropdown-item" href="{{ route('logout') }}"
   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
    {{ __('Logout') }}
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
```

**Note:** If it throws error that: `Vite manifest not found at: C:\xampp\htdocs\employee-management-system\public\build/manifest.json`,
then run this npm command: `npm run build`. It should solve the issue.


## Setup for Permissions

### Create Permission Controller

`php artisan make:controller PermissionController -r`

### Unguard the columns in the Permission model

```php
class Permission extends Model
{
    protected $guarded = [];
}
```

### Create Resource route for Permissions

- It should be under middleware auth.

```php
Route::resource('permissions', PermissionController::class);
```

## Permission Form

### PermissionController@create

```php
    public function create()
    {
        // $permissionList is an array in PermissionController class.
        // $permissionList = ['department','role','permission','user',]
        $permissionList = $this->permissionList;
        return view('admin.permission.create', compact('permissionList'));
    }
```

### permission/create.blade.php

- It extends the `master.blade.php` and have card layout from `home.blade.php`.
- Create a form which hits the `permissions.store` route.
- Create a `<select>` to select the role.
- It has these permission options: `can-add; can-edit; can-view; can-delete; can-list`.
- Create a `<table>` with these permission options as `checkbox` input for each role.
- Each `checkbox` input should have a name which is specific to each department and permission.
For example: `<input name="[department][can-edit]" type="checkbox" value="1">`. It has a default value of 1.
- Instead of writing for each department, we iterate over the roles and display it as:

    ```bladehtml
    <tbody>
        @foreach(\App\Models\Role::all() as $role)
    
        <tr>
            <td>{{$role->name}}</td>
            <td><input type="checkbox" name="name[{{strtolower($role->name)}}][can-add]" value="1"></td>
            <td><input type="checkbox" name="name[{{strtolower($role->name)}}][can-edit]" value="1"></td>
            <td><input type="checkbox" name="name[{{strtolower($role->name)}}][can-view]" value="1"></td>
            <td><input type="checkbox" name="name[{{strtolower($role->name)}}][can-delete]" value="1"></td>
            <td><input type="checkbox" name="name[{{strtolower($role->name)}}][can-list]" value="1"></td>
    
        </tr>
    
        @endforeach
    </tbody>
    ```

- Create a submit button.

```bladehtml
<!--permission/create.blade.php-->
@extends('admin.layouts.master')

@section('content')

<div class="container mt-3">
    <div class="row justify-content-center">

        @if(Session::has('message'))
        <div class="alert alert-success" style="width: 90%">
            {{Session::get('message')}}
        </div>
        @endif

        <div class="col-md-8">
            <form method="post" action="{{route('permissions.store')}}"> @csrf
                <div class="card">
                    <div class="card-header">{{ __('Permissions') }}</div>

                    <div class="card-body">

                        {{--    Roles Dropdown  --}}

                        <select class="form-select @error('role_id') is-invalid @enderror" name="role_id">
                            @foreach(\App\Models\Role::all() as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror

                        {{--    Table for the permission    --}}

                        <table class="table table-dark table-striped mt-4">

                            <thead>
                            <tr>
                                <th scope="col">Permission</th>
                                <th scope="col">can-add</th>
                                <th scope="col">can-edit</th>
                                <th scope="col">can-view</th>
                                <th scope="col">can-delete</th>
                                <th scope="col">can-list</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($permissionList as $permissionItem)

                            <tr>
                                <td>{{$permissionItem}}</td>
                                <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-add]" value="1"></td>
                                <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-edit]" value="1"></td>
                                <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-view]" value="1"></td>
                                <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-delete]" value="1"></td>
                                <td><input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-list]" value="1"></td>

                            </tr>

                            @endforeach
                            </tbody>

                        </table>

                        <div class="text-center">
                            <button class="btn btn-primary" type="submit">Submit</button>

                            <div class="float-end">
                                <a href="{{route('permissions.index')}}">
                                    <span class="badge bg-secondary">Back</span>
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection

```


### Save all the permissions in json using Laravel Casts

- Go to the Permission model and add this code:

```php
    protected $casts = [
        'name' => 'array'
    ];
```

- What this `casting` does is that it converts the string into json format for each role id. 
- For example: `{"department":{"can-add":"1","can-edit":"1","can-view":"1","can-delete":"1","can-list":"1"},"role":{"can-add":"1","can-edit":"1","can-view":"1","can-delete":"1","can-list":"1"},"permission":{"can-add":"1","can-edit":"1","can-view":"1","can-delete":"1","can-list":"1"},"user":{"can-add":"1","can-edit":"1","can-view":"1","can-delete":"1","can-list":"1"}}`
will be converted into a `map` format. Later on, it will be straightforward to
use these like a normal map.
- Now store it in the following way.

## PermissionController@store

- Validate that `role_id` has only one submission.

```php
    public function store(Request $request)
    {
        $this->validate($request,[
            'role_id' => 'required|unique:permissions,role_id',
        ]);

        Permission::create($request->all());
        return redirect()->back()->with('message','Permission created successfully');

    }
```

## Get all permissions

### PermissionController@index

- Get the permissions.
- Return permission/index.blade.php

```php
// PermissionController@index
    public function index()
    {
        $permission = Permission::get();
        return view('admin.permission.index', compact('permission'));
    }
```

### permission/index.blade.php

- Create `permission/index.blade.php`.
- Copy everything from `department/index.blade.php`.
- Change the heading to `All Permissions`.
- Replace the `$departments` to `$permissions` and `$department` to
`$permission`.
- Modify the table as there are only four columns:
  - SN
  - Name
  - Edit button
  - Delete button

```bladehtml
<!--permission/index.blade.php-->
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
                    All Permissions
                </div>
                <div class="card-body">

                    @if(count($permissions)>0)

                    <table id="datatablesSimple">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Edit</th>
                            <th>Delete</th>

                        </tr>
                        </thead>

                        <tbody>

                        @foreach($permissions as $key=>$permission)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$permission->role->name}}</td>
                            <td>
                                <a href="{{route('permissions.edit', $permission->id)}}">
                                    <div class="p-1">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$permission->id}}">
                                    <div class="p-1">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </a>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal{{$permission->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{route('permissions.destroy', $permission->id)}}" method="post">
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

                        </tr>
                        @endforeach

                        </tbody>

                    </table>

                    @else
                    No permissions found!
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


```

## Edit permissions

### PermissionController@edit

```php
// PermissionController@edit
    public function edit(string $id)
    {
        // $permissionList is an array in PermissionController class.
        // $permissionList = ['department','role','permission','user',]
        $permissionList = $this->permissionList;
        $permission = Permission::find($id);
        return view('admin.permission.edit', compact('permission', 'permissionList'));
    }
```

### permission/edit.blade.php

- Copy everything from `permission/create.blade.php`
- Update the `<form action="{{route('permissions.update', $permission->id)}}"`
- Method should be PATCH: `@method('PATCH`).
- For every `<input type="checkbox">`, check if the key-value pair exists
in the order of `name[department][can-edit]`, then it should be checked 
else unchecked(default). Use `isset()` method to check it. For example,
`<input type="checkbox" name="name[{{strtolower($permissionItem)}}][can-add]" value="1"
@if(isset($permission['name'][$permissionItem]['can-add'])) checked @endif>`

```bladehtml 
<!--permission/edit.blade.php-->

```

### PermissionController@update

```php
    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);
        $permission = Permission::find($id);
        $permission->update($request->all());
        return redirect()->back()->with('message','Permission updated successfully');
    }
```

### PermissionController@destroy

```php
    public function destroy(string $id)
    {
        Permission::find($id)->delete();
        return redirect()->back()->with('message','Permission deleted successfully');
    }
```


## Hide and Show links based on Permission `Important`

### Display User name in Navbar

- Go to `layouts/navbar.blade.php`.
- Add this code to display username: `{{Auth()->user()->name}}`.

### Method to hide the links based on permissions

- Permissions are stored as an associated array for each role.
- For example, for the admin role, these are the permissions.
```php
{
    "department":{
        "can-add":"1",
        "can-edit":"1",
        "can-view":"1",
        "can-delete":"1",
        "can-list":"1"
    },
    "role":{
        "can-add":"1",
        "can-edit":"1",
        "can-view":"1",
        "can-delete":"1",
        "can-list":"1"
    },
    "permission":{
        "can-add":"1",
        "can-edit":"1",
        "can-view":"1",
        "can-delete":"1",
        "can-list":"1"
    },
    "user":{
        "can-add":"1",
        "can-edit":"1",
        "can-view":"1",
        "can-delete":"1",
        "can-list":"1"
    }
}
```

- If a particular permission exists or not, can be verified using
the isset() method in any `blade.php` file. For example, the logged in user can edit 
a department or not, can be verified as:
  - ```php
        @if(isset(auth()->user()->role->permission['name']['department']['can-edit']))
    ```
- It returns `1` if that permission exists and `0` if it does not.

- Based on this, we use this method to check for every button or link 
that hits a particular route.
  - Use this `conditional code snippet` in every `edit` and `delete` button in all the `index` pages.
  - Use this in the sidebar links in the same way.

## Protect the routes using Middleware

Steps:

- Create a `trait` at `app/Traits/permissionTrait.php`.
- Create a method named `hasPermission()` in this trait.
- The `hasPermission()` method will check for the required permission
for different route requests.
- Create a `middleware` named `HasPermission`.
- Use the `hasPermission()` method from trait in the middleware.
- Register `HasPermission` middleware in `bootstrap/app.php`.
- Add this middle in the `web.php` file to protect routes.


### Create Trait

- Create `app/Traits/permissionTrait.php`
- Code it in this way::

```php
// app/Traits/permissionTrait.php
<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;

trait permissionTrait {
    public function hasPermission(){

        $permissionList = ['department', 'role', 'permission', 'user',];
        $permissions = ['can-add', 'can-edit', 'can-delete', 'can-view', 'can-list'];
        $crudRoutes = ['create', 'store', 'edit', 'index', 'update', 'delete'];

        foreach ($permissionList as $permissionItem){
            if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-add']) && (Route::is($permissionItem.'s.create') || Route::is($permissionItem.'s.store') )){
                return abort(401);
            }

            if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-edit']) && (Route::is($permissionItem.'s.edit') || Route::is($permissionItem.'s.update') )){
                return abort(401);
            }

            if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-view']) && Route::is($permissionItem.'s.index') ){
                return abort(401);
            }

            if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-delete']) && Route::is($permissionItem.'s.delete') ){
                return abort(401);
            }


        }

    }
}

```

### Create middleware

- `php artisan make:middleware HasPermission`
- Code it in this way:

```php
// app/Http/Middleware/HasPermission.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\permissionTrait;

class HasPermission
{
    use permissionTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->hasPermission();
        return $next($request);
    }
}

```

### Register the middleware

```php
// bootstrap/app.php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\HasPermission::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### Add the middleware in `web.php`

```php
// routes/web.php
Route::middleware(['auth', HasPermission::class])->group(function () {
    // routes
}
```

[//]: # (Permissions ends here)

<hr>

# Employee Leave

## Prequisites

### Create LeaveController

```php 
php artisan make:controller LeaveController -r
```

###  Unguard the elements in `Leave` model.

```php
class Leave extends Model
{
    protected $guarded = [];
}
```

### Create reource route for Leave

```php
    Route::resource('leaves', LeaveController::class);
```

### Using JqueryUI Date picker

- Copy this `js` link: `<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">` from official website [https://api.jqueryui.com/datepicker/#entry-examples].
- Paste it in `navbar.blade.php`.
- Again, copy the `<script>` from official website and paste it in
`footer.blade.php`.
    ```bladehtml
    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI Library -->
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script>
        $( "#datepicker" ).datepicker();
    </script>
    <script>
        $( "#datepicker1" ).datepicker();
    </script>
    <!-- Here two scripts are for two dates, such as in Leave form, from date1 to date2 -->
    ```
- In the `<input>` field that selects date,
  - don't define the `<input type="">`
  - provide the `<input id="datepicker">` as `datepicker`.
  
- Do this input modification in `user/create` and `user/edit`.

#### Changing the format of date

- For this, we need to modify this:

```bladehtml
    <script>
        $( "#datepicker" ).datepicker({dateFormat:"yy-mm-dd"}).val();
    </script>
```

## Create Leave form

### LeaveController@create

- Find all the previous leaves of the user.
- View the `create` page along with his `leaves`.

```php
// LeaveController@create
    public function create()
    {
        $leaves = Leave::latest()->where('user_id', auth()user()->id)->get();
        return view('admin.leave.create', compact('leaves'));
    }
```

### leave/create.blade.php

```bladehtml
<!--leave/create.blade.php-->


```

### LeaveController@store

- Validate the fields.
- Get the request data in a new variable.
- Add more fields data into that variable such as
user_id, status and reply message.
- Save it in database.
- Return redirect back with a success message.

```php
// LeaveController@store
    public function store(Request $request)
    {
        $this->validate($request, [
            'from' => 'required',
            'to' => 'required|after:from',
            'type' => 'required',
            'description' => 'required'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 0; // pending
        $data['message'] = '';
        Leave::create($data);

        return redirect()->back()->with('message', 'Leave Requested Successfully');
    }
```

###

```php
// LeaveController@edit
    public function edit(string $id)
    {
        if(Leave::find($id)->status != 0)
        {
            return redirect()->back()->with('message', 'Leave is already approved!');
        }

        $leave = Leave::find($id);
        return view('admin.leave.edit', compact('leave'));
    }
```

### leave/edit.blade.php

```bladehtml
<!--leave/edit.blade.php-->
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

            <div class="card border-info">
                <div class="card-header">{{ __('Update Leave') }}</div>

                <div class="card-body">

                    <form method="post" action="{{route('leaves.update', $leave->id)}}"> @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>From</label>
                            <input id="datepicker" class="form-control @error('from') is-invalid @enderror" name="from" value="{{$leave->from}}">
                            @error('from')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>To</label>
                            <input id="datepicker1" class="form-control @error('to') is-invalid @enderror" name="to" value="{{$leave->to}}">
                            @error('to')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Type of Leave</label>
                            <select class="form-control" name="type">
                                <option value="annualleave" @if($leave->type == "annualleave") selected @endif>Annual Leave</option>
                                <option value="sickleave" @if($leave->type == "sickleave") selected @endif>Sick Leave</option>
                                <option value="parentalleave" @if($leave->type == "parentalleave") selected @endif>Parental Leave</option>
                                <option value="other" @if($leave->type == "other") selected @endif>Other Leave</option>
                            </select>
                            @error('type')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description">{{$leave->description}}</textarea>
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

### LeaveController@update

```php
// LeaveController@update
    public function update(Request $request, string $id)
    {
        if(Leave::find($id)->status != 0)
        {
            return redirect()->back()->with('message', 'Leave is already approved!');
        }

        $this->validate($request, [
            'from' => 'required|date|after:today',
            'to' => 'required|after:from',
            'type' => 'required',
            'description' => 'required'
        ]);

        $leave = Leave::find($id);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 0; // pending
        $data['message'] = '';
        $leave->update($data);

        return redirect()->route('leaves.create')->with('message', 'Leave Requested Successfully');
    }
```

### LeaveController@destroy

```php
// LeaveController@destroy
    public function destroy(string $id)
    {
        if(Leave::find($id)->status != 0)
        {
            return redirect()->back()->with('message', 'Leave is already approved!');
        }

        Leave::find($id)->delete();
        return redirect()->back()->with('message', 'Leave Deleted!');
    }
```

## Accept or Reject Leaves

- We do it from `leaves/index.blade.php`.

### LeaveController@index

```php
// LeaveController@index

```

### leave/indexblade.php

- Copy everything from `home.blade.php`.
- Change the `@extends()` to use our `master.blade.php` template.
- Also change the Heading.
- Copy the table from `leave/create.blade.php`.
- Modify the table as:
  - First column displays the name of the User who applied for leave.
  - Remove the `Edit` and `Delete` button with a `Approve/Reject` button.
  - When `Approve/Reject` button is hit, is shows a popup for confirmation.
  - This popup has a `<select>` for `Approve` and `Reject`.
  - It has a `<textarea>` for `reply` message.

```php
// index.blade.php
@extends('admin.layouts.master')

@section('content')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-11">

                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{Session::get('message')}}
                    </div>
                @endif
                
                <div class="card">
                    <div class="card-header"><strong>{{ __('Leaves') }}</strong></div>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Date From</th>
                            <th scope="col">Date To</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Reply</th>
                            <th scope="col">Approve/Reject</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leaves as $key=>$leave)
                            <tr>
                                <td>{{$leave->user->name}}</td>
                                <td>{{$leave->from}}</td>
                                <td>{{$leave->to}}</td>
                                <td>{{$leave->type}}</td>
                                <td><div style="width: 200px; max-height: 100px;">{{$leave->description}}</div></td>
                                <td>
                                    @if($leave->status == 0)
                                        <div class="badge bg-secondary">Pending</div>
                                    @elseif ($leave->status == 1)
                                        <div class="badge bg-success">Approved</div>
                                    @elseif ($leave->status == 2)
                                        <div class="badge bg-danger">Rejected</div>
                                    @endif
                                </td>
                                <td>{{$leave->message}}</td>
                                <td>
                                    @if($leave->status == 0)
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$leave->id}}">
                                            <div class="p-1">
                                                <button class="btn btn-info"> Approve/Reject</button>
                                            </div>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$leave->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{route('accept-reject-leave', $leave->id)}}" method="post">
                                                    @csrf

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirm Leave</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="">Select</option>
                                                                    <option value="1">Approve</option>
                                                                    <option value="2">Reject</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mt-1">
                                                                <label>Reply message</label>
                                                                <textarea class="form-control mt-1 " placeholder="Reply..." name="message"></textarea>
                                                            </div>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-success">Confirm</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Modal End -->
                                    @endif

                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
@endsection

```

### Create a post-route for `accept/reject` leave

```php
Route::post('accept-reject-leave/{id}', [LeaveController::class, 'acceptRejectLeave'])->name('accept-reject-leave');
```

### LeaveController@acceptRejectLeave

```php
// LeaveController@acceptRejectLeave
    public function acceptRejectLeave(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required',
            'message' => 'required'
        ]);

        $leave = Leave::find($id);
        if($leave->status != 0)
        {
            return redirect()->back()->with('message', 'Leave is already approved');
        }

        $status = $request->status;
        $message = $request->message;
        $leave->update([
            'status' => $status,
            'message' => $message
        ]);

        return redirect()->back()->with('message', 'Leave updated');

    }
```


### Modify Sidebar to show Leave links

Now the sidebar is structured as:
- Sidebar
  - Dashboard
  - Departments
    - Create
    - View
  - User
    - Roles
      - Create Roles
      - View Roles
    - Permissions
      - View Permission
      - Create Permission
  - Leaves
    - Approve
    - Create

```bladehtml
<!--resources/views/admin/layouts/sidebar.blade.php-->
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link" href="{{url('/')}}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">Interface</div>
                    @if(isset(auth()->user()->role->permission['name']['department']['can-view']))
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Departments
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                @if(isset(auth()->user()->role->permission['name']['department']['can-add']))
                                    <a class="nav-link" href="{{route('departments.create')}}">Create</a>
                                @endif
                                <a class="nav-link" href="{{route('departments.index')}}">View</a>
                            </nav>
                        </div>
                    @endif

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        User
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">

                            @if(isset(auth()->user()->role->permission['name']['role']['can-view']))
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                Roles
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    @if(isset(auth()->user()->role->permission['name']['role']['can-add']))
                                        <a class="nav-link" href="{{route('roles.create')}}">Create Roles</a>
                                    @endif
                                    @if(isset(auth()->user()->role->permission['name']['role']['can-view']))
                                        <a class="nav-link" href="{{route('roles.index')}}">View Roles</a>
                                    @endif
                                </nav>
                            </div>
                            @endif

                            @if(isset(auth()->user()->role->permission['name']['permission']['can-view']))
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                Permissions
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{route('permissions.index')}}">View Permission</a>
                                    @if(isset(auth()->user()->role->permission['name']['permission']['can-add']))
                                        <a class="nav-link" href="{{route('permissions.create')}}">Create Permission</a>
                                    @endif
                                </nav>
                            </div>
                            @endif
                        </nav>
                    </div>

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsLeave" aria-expanded="false" aria-controls="collapseLayoutsLeave">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Leaves
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayoutsLeave" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">

                        <nav class="sb-sidenav-menu-nested nav">
                            @if(isset(auth()->user()->role->permission['name']['leave']['can-list']))
                                <a class="nav-link" href="{{route('leaves.index')}}">Approve Leave</a>
                            @endif
                        </nav>
                        <nav class="sb-sidenav-menu-nested nav">
                            @if(isset(auth()->user()->role->permission['name']['leave']['can-add']))
                                <a class="nav-link" href="{{route('leaves.create')}}">Create Leave</a>
                            @endif
                        </nav>
                    </div>


                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                Start Bootstrap
            </div>
        </nav>
    </div>

```

### Protect `leaves.accept-reject-leave` route from unauthorized access

- In the `PermissionController` and `permissionTrait`, add `leaves`
in `PermissionList` array.
- Now go to `permissions.edit` route for `admin` and update the admin
permissions for leaves.
- And since only admin can approve or reject leaves, we don't need to 
modify any other leaves.
- Also, modify `permissionTrait` as if the one who can list the `Leaves` can
can also approve/reject it. 
    ```php
    // app/Traits/permissionTrait.php
    if(!isset(auth()->user()->role->permission['name'][$permissionItem]['can-list']) && (Route::is($permissionItem.'s.index') || Route::is('accept-reject-leave')) ){
        return abort(401);
    }
    ```

[//]: # (Employee Leave Completed)
<hr>

# Notice

## Setup

### Create a controller

`php artisan make:controller NoticeController -r`

### Create a resource route

```php
// web.php
Route::resource('notices', NoticeController::class);
```

### Unguard the elements in model

```php
// Notice Model
class Notice extends Model
{
    protected $guarded = [];
}

```

## CRUD for Notice

### NoticeController@create

View the file here: [NoticeController@create](./app/Http/Controllers/NoticeController.php)

```php
// NoticeController@create
    public function create()
    {
        return view('admin.notice.create');
    }
```

### notice/create.blade.php

```bladehtml
<!--notice/create.blade.php-->
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
                <div class="card-header">{{ __('Create Notice') }}</div>

                <div class="card-body">

                    <form method="post" action="{{route('notices.store')}}"> @csrf

                        <div class="form-group">
                            <label>Title</label>
                            <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{old('title')}}">
                            @error('title')
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

                        <div class="form-group">
                            <label>Date</label>
                            <input type="text" class="form-control @error('date') is-invalid @enderror" id="datepicker"
                                   name="date"
                                   value="{{old('date')}}">
                            @error('date')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        {{--                            <div class="form-group">--}}
                            {{--                                <label>Name</label>--}}
                            {{--                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{old('name')}}">--}}
                            {{--                                @error('name')--}}
                            {{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
                            {{--                                @enderror--}}
                            {{--                            </div>--}}


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

### NoticeController@store

```php
// NoticeController@store
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date|after:today',
//            'name' => 'required|max:100'
        ]);

        $data = $request->all();
        $data['name'] = auth()->user()->name;
        Notice::create($data);
        return redirect()->back()->with('message', 'Notice Create Successfully');
    }

```

### NoticeController@index

```php
// NoticeController@index

```

### notice/index.blade.php

```bladehtml
<!--notice/index.blade.php-->
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

            <div class="alert alert-secondary">All Notices</div>

            @foreach($notices as $notice)

            <div class="card mb-3 alert alert-primary">

                <div class="card-header m-2 bg-warning">
                    {{$notice->title}}
                </div>

                <div class="card-body">

                    <div class="alert">{{$notice->description}}</div>
                    <div class="badge bg-success">{{$notice->date}}</div>
                    <div class="badge bg-warning text-black">{{$notice->name}}</div>
                </div>

                @if(isset(auth()->user()->role->permission['name']['notice']['can-edit']))
                <div class="card-footer">
                    <div class="float-start">
                        {{--                                @if(isset(auth()->user()->role->permission['name']['notice']['can-edit']))--}}
                        <a href="{{route('notices.edit', $notice->id)}}">
                            <div class="p-1">
                                <i class="fas fa-edit"></i>
                            </div>
                        </a>
                        {{--                                @endif--}}
                    </div>
                    <div class="float-end">
                        {{--                                @if(isset(auth()->user()->role->permission['name']['notice']['can-delete']))--}}
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$notice->id}}">
                            <div class="p-1">
                                <i class="fas fa-trash"></i>
                            </div>
                        </a>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{$notice->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{route('notices.destroy', $notice->id)}}" method="post">
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
                        {{--                                @endif--}}
                    </div>

                </div>
                @endif

            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection

```

### NoticeController@edit

```php
// NoticeController@edit

```
### notice/edit.blade.php

```bladehtml
<!--notice/edit.blade.php-->

```

### NoticeController@update

```php
// NoticeController@update

```

### NoticeController@destroy
```php
// NoticeController@destroy

```

## Update permissions for notices

### `PermissionController` & `permissionTrait`

- Update the `PermissionList` array by adding `notice` element in each
of these files.

### Update the permissions for every role.

- Update all the permissions by going to 'permissions/index' route.


## Update the Sidebar

- Sidebar
  - ...
  - ...
  - Notice
    - All Notices
    - Create

### Hide the links

- Hide the links in UI for different roles using this code snippet.

```bladehtml
@if(isset(auth()->user()->role->permission['name']['notice']['can-list']))
    <a class="nav-link" href="{{route('notices.index')}}">All Notices</a>
    ... other codes go on //
@endif
```

[//]: # (Notice completed)
<hr>


# Bulk Email

## Mail Setup

### Create MailController

`php artisan make:controller MailController` 

### Create a Mail

`php artisan make:mail SendMail`

### Get Google App password

- Go to https://myaccount.google.com/security.
- Ensure your gmail has 2-steps verification.
- Then search for `App Password`.
- Generate a nwe App password.
- Copy it.
- Then setup `.env` file for mail as follows.

### Mail Details setup in `.env` file

```
// .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=__YourEmail__
MAIL_PASSWORD=__YourAppPassword__
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=__YourEmail__
MAIL_FROM_NAME="${APP_NAME}"
```

### Create get route 

- Create a get route for mail:

```php
// web.php
    Route::get('mails', [MailController::class, 'create'])->name('mails.create');
```

### MailController@create

```php

    public function create()
    {
        return view('admin.email.create');
    }
```

### mail/create.blade.php

```bladehtml
<!--email/create.blade.php-->
@extends('admin.layouts.master')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(Session::has('message'))
            <div class="alert alert-success">
                {{Session::get('message')}}
            </div>
            @endif

            <div class="card">
                <div class="card-header">{{ __('Send Mail') }}</div>

                <div class="card-body">

                    <form action="{{route('mails.store')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Select</label>
                            <select id="email" class="form-control mt-2">
                                <option value="0">Mail to all staffs</option>
                                <option value="1">Choose Department</option>
                                <option value="2">Choose Person</option>
                            </select>
                            @error('department')
                            <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                            @enderror
                            @error('person')
                            <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                            @enderror

                            <select id="department" class="form-control mt-2 @error('department') is-invalid @enderror" name="department">
                                <option value="">Select Department</option>
                                @foreach(\App\Models\Department::all() as $department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                            </select>
                            @error('department')
                            <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                            @enderror

                            <select id="person" class="form-control mt-2 @error('person') is-invalid @enderror" name="person">
                                <option value="">Select Person</option>
                                @foreach(\App\Models\User::all() as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            @error('person')
                            <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span>
                            @enderror

                            <div class="form-group mt-2">
                                <label>Body</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" name="body">

                                    </textarea>
                                @error('body')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                @enderror
                            </div>

                            <div class="form-group mt-2">
                                <label>File</label>
                                <input class="form-control @error('body') is-invalid @enderror" type="file" name="file">
                                @error('file')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                @enderror
                            </div>

                            <div class="form-group text-center mt-2">
                                <button class="btn btn-primary" type="submit">
                                    Send Mail
                                </button>
                            </div>




                        </div>


                    </form>



                </div>
            </div>
        </div>
    </div>
</div>

{{--    Some css style to hide the department and person div--}}
<style>
    #department{
        display: none;
    }
    #person{
        display: none;
    }
</style>

@endsection

```

- Add some javascript in `admin/layouts/footer.blade.php` 
to make hide and show effective.

```bladehtml
<!-- hide and show in male.blade.php -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#email').on('change', function () {
            const value = this.value;

            if (value == "1") {
                $("#department").show();
                $("#person").hide();
            } else if (value == "2") {
                $("#person").show();
                $("#department").hide();
            } else {
                $("#department").hide();
                $("#person").hide();
            }
        });
    });
</script>

```


## Send Email with attachment

### Create a route for storing email

```php
// web.php
Route::post('mails', [MailController::class, 'store'])->name('mails.store');
```

### Create a mail `SendMail`

`php atisan make:mail SendMail`

- This will create a file at: `app/Mail/SendMail.php`.
- This is a special mail file specifically for sending emails.

### MailController@store

```php
// MailController@store
    public function store(Request $request)
    {
        $this->validate($request,[
            'file'=>'mimes:pdf,docx,doc,jpeg,jpg,png',
            'body'=>'required'
        ]);

        $file = $request->file('file');

        $details = [
            'body' => $request->body,
            'file' => $request->file('file'),
        ];


        if($request->department)
        {
            $this->validate($request, [
                'department' => 'required',
            ]);
            $users = User::where('department_id', $request->department);
            foreach ($users as $user) {
                Mail::to($user->email)->send(new SendMail($details));
            }
        } elseif($request->person)
        {
            $this->validate($request, [
                'person' => 'required',
            ]);
            $user = User::find($request->person);
            $userEmail = $user->email;
            Mail::to($userEmail)->send(new SendMail($details));
        } else
        {
            $users = User::get();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new SendMail($details));
            }
        }

        return redirect()->back()->with('message', 'Mail Sent Successfully');

```

### SendMail.php

```php
// app/Mail/SendMail.php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;

    /**
     * Create a new message instance.
     */
    public function __construct($details = [])
    {
        $this->details = $details;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Mail From Laravel Employee Management System",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.email.sendmail',

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->details['file']->getRealPath())
                ->as($this->details['file']->getClientOriginalName())
                ->withMime($this->details['file']->getMimeType()),
        ];
    }
}

```

### resources/views/admin/email/sendmail.blade.php

```bladehtml
<!DOCTYPE html>
<html>

<head>
    <title>Mail from LaravelEMS</title>
</head>

<body>
    <p>{{$details['body']}}</p>
    <p>Thank you!</p>
</body>

</html>
```


## Configure Permission for Mails

### Authenticate the route

- Add `mail` in `PermissionList` array in these two files:
  - `PermissionController.php`
  - `permissionTrait.php`
- Then, edit permissions for each role by going to `permissions.index` route.

### Add `Mail` in Sidebar

- Sidebar
  - ...
  - ...
  - Mail `mails.create route`

### Hide and show the links

Use this syntax

```bladehtml
@if(isset(auth()->user()->role->permission['name']['mail']['can-add']))
<a class="nav-link" href="{{route('mails.create')}}">
    <div class="sb-nav-link-icon"><i class="fa fa-envelope"></i></div>
    Mail
</a>
@endif
```

[//]: # (Bulk Email completed)
<hr>

# Dashboard

## Add links to different Cards

- Go to `resources/views/admin/layouts/content.blade.php`.
This is the default dashboard of the bootstrap admin template we
are using. We will modify this page.
- Show links for different Routes such as: Users, Dashboards,
Notices, Links etc.
- Show the logged-in User Profile.
- Modify more as needed.

This is final Dashboard: `resources/views/admin/layouts/content.blade.php`

```bladehtml
resources/views/admin/layouts/content.blade.php

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4 ">
                        <div class="card-body text-center">
                            <p><i class="fas fa-user fa-fw" style="font-size: 100px;"></i></p>
                            Users
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{route('users.index')}}" style="font-size: 18px;">
                                Total: {{App\Models\User::all()->count()}}
                            </a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body text-center">
                            <p><i class="fas fa-home" style="font-size: 100px;"></i></p>
                            Departments
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{route('departments.index')}}"  style="font-size: 18px;">
                                Total: {{App\Models\Department::all()->count()}}
                            </a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body text-center">
                            <p><i class="fas fa-envelope" style="font-size: 100px;"></i></p>
                            Notice
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{route('notices.index')}}"  style="font-size: 18px;">
                                Total: {{App\Models\Notice::all()->count()}}
                            </a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body text-center">
                            <p><i class="fas fa-book" style="font-size: 100px;"></i></p>
                            Leave
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{route('leaves.index')}}"  style="font-size: 18px;">
                                Total: {{App\Models\Leave::all()->count()}}
                            </a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header m-2">
                            User Info
                        </div>
                        <div class="card-body">
                            <div class="custom-card">
                                <div class="card-body">
                                    <img src="{{asset('profile/'.$user->image)}}" alt="Profile Image" class="profile-image">
                                    <div class="ms-4 me-4">
                                        <h5>{{$user->name}}</h5>
                                        <p class="text-muted">{{$user->email}}</p>
                                        <h6>{{$user->address}}</h6>

                                    </div>
                                    <div class="ms-4 me-4">
                                        <label>Designation</label>
                                        <h6>{{$user->designation}}</h6>

                                        <label>Contact</label>
                                        <h6>{{$user->mobile_number}}</h6>


                                    </div>
                                    <div class="ms-4 me-4">
                                        <label>Department</label>
                                        <h6>{{$user->department->name}}</h6>

                                        <label>Role</label>
                                        <h6>{{$user->role->name}}</h6>
                                    </div>
                                </div>
                            </div>

                            {{--                            css             --}}
                            <style>
                                .custom-card {
                                    background-color: #e7f3ff;
                                    border-radius: 10px;
                                    padding: 20px;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                }
                                .custom-card .card-body {
                                    display: flex;
                                    align-items: center;
                                }
                                .profile-image {
                                    border-radius: 50%;
                                    width: 100px;
                                    height: 100px;
                                    margin-right: 20px;
                                }
                                .custom-stats {
                                    display: flex;
                                    justify-content: space-between;
                                    margin-top: 15px;
                                }
                                .custom-stats div {
                                    text-align: center;
                                }
                                .custom-stats .value {
                                    font-weight: bold;
                                    font-size: 1.2rem;
                                }
                                .btn-group {
                                    margin-top: 10px;
                                }
                            </style>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-area me-1"></i>
                            Area Chart Example
                        </div>
                        <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Bar Chart Example
                        </div>
                        <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    DataTable Example
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                        </tr>
                        <tr>
                            <td>Garrett Winters</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>63</td>
                            <td>2011/07/25</td>
                            <td>$170,750</td>
                        </tr>
                        <tr>
                            <td>Ashton Cox</td>
                            <td>Junior Technical Author</td>
                            <td>San Francisco</td>
                            <td>66</td>
                            <td>2009/01/12</td>
                            <td>$86,000</td>
                        </tr>
                        <tr>
                            <td>Cedric Kelly</td>
                            <td>Senior Javascript Developer</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2012/03/29</td>
                            <td>$433,060</td>
                        </tr>
                        <tr>
                            <td>Airi Satou</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>33</td>
                            <td>2008/11/28</td>
                            <td>$162,700</td>
                        </tr>
                        <tr>
                            <td>Brielle Williamson</td>
                            <td>Integration Specialist</td>
                            <td>New York</td>
                            <td>61</td>
                            <td>2012/12/02</td>
                            <td>$372,000</td>
                        </tr>
                        <tr>
                            <td>Herrod Chandler</td>
                            <td>Sales Assistant</td>
                            <td>San Francisco</td>
                            <td>59</td>
                            <td>2012/08/06</td>
                            <td>$137,500</td>
                        </tr>
                        <tr>
                            <td>Rhona Davidson</td>
                            <td>Integration Specialist</td>
                            <td>Tokyo</td>
                            <td>55</td>
                            <td>2010/10/14</td>
                            <td>$327,900</td>
                        </tr>
                        <tr>
                            <td>Colleen Hurst</td>
                            <td>Javascript Developer</td>
                            <td>San Francisco</td>
                            <td>39</td>
                            <td>2009/09/15</td>
                            <td>$205,500</td>
                        </tr>
                        <tr>
                            <td>Sonya Frost</td>
                            <td>Software Engineer</td>
                            <td>Edinburgh</td>
                            <td>23</td>
                            <td>2008/12/13</td>
                            <td>$103,600</td>
                        </tr>
                        <tr>
                            <td>Jena Gaines</td>
                            <td>Office Manager</td>
                            <td>London</td>
                            <td>30</td>
                            <td>2008/12/19</td>
                            <td>$90,560</td>
                        </tr>
                        <tr>
                            <td>Quinn Flynn</td>
                            <td>Support Lead</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2013/03/03</td>
                            <td>$342,000</td>
                        </tr>
                        <tr>
                            <td>Charde Marshall</td>
                            <td>Regional Director</td>
                            <td>San Francisco</td>
                            <td>36</td>
                            <td>2008/10/16</td>
                            <td>$470,600</td>
                        </tr>
                        <tr>
                            <td>Haley Kennedy</td>
                            <td>Senior Marketing Designer</td>
                            <td>London</td>
                            <td>43</td>
                            <td>2012/12/18</td>
                            <td>$313,500</td>
                        </tr>
                        <tr>
                            <td>Tatyana Fitzpatrick</td>
                            <td>Regional Director</td>
                            <td>London</td>
                            <td>19</td>
                            <td>2010/03/17</td>
                            <td>$385,750</td>
                        </tr>
                        <tr>
                            <td>Michael Silva</td>
                            <td>Marketing Designer</td>
                            <td>London</td>
                            <td>66</td>
                            <td>2012/11/27</td>
                            <td>$198,500</td>
                        </tr>
                        <tr>
                            <td>Paul Byrd</td>
                            <td>Chief Financial Officer (CFO)</td>
                            <td>New York</td>
                            <td>64</td>
                            <td>2010/06/09</td>
                            <td>$725,000</td>
                        </tr>
                        <tr>
                            <td>Gloria Little</td>
                            <td>Systems Administrator</td>
                            <td>New York</td>
                            <td>59</td>
                            <td>2009/04/10</td>
                            <td>$237,500</td>
                        </tr>
                        <tr>
                            <td>Bradley Greer</td>
                            <td>Software Engineer</td>
                            <td>London</td>
                            <td>41</td>
                            <td>2012/10/13</td>
                            <td>$132,000</td>
                        </tr>
                        <tr>
                            <td>Dai Rios</td>
                            <td>Personnel Lead</td>
                            <td>Edinburgh</td>
                            <td>35</td>
                            <td>2012/09/26</td>
                            <td>$217,500</td>
                        </tr>
                        <tr>
                            <td>Jenette Caldwell</td>
                            <td>Development Lead</td>
                            <td>New York</td>
                            <td>30</td>
                            <td>2011/09/03</td>
                            <td>$345,000</td>
                        </tr>
                        <tr>
                            <td>Yuri Berry</td>
                            <td>Chief Marketing Officer (CMO)</td>
                            <td>New York</td>
                            <td>40</td>
                            <td>2009/06/25</td>
                            <td>$675,000</td>
                        </tr>
                        <tr>
                            <td>Caesar Vance</td>
                            <td>Pre-Sales Support</td>
                            <td>New York</td>
                            <td>21</td>
                            <td>2011/12/12</td>
                            <td>$106,450</td>
                        </tr>
                        <tr>
                            <td>Doris Wilder</td>
                            <td>Sales Assistant</td>
                            <td>Sidney</td>
                            <td>23</td>
                            <td>2010/09/20</td>
                            <td>$85,600</td>
                        </tr>
                        <tr>
                            <td>Angelica Ramos</td>
                            <td>Chief Executive Officer (CEO)</td>
                            <td>London</td>
                            <td>47</td>
                            <td>2009/10/09</td>
                            <td>$1,200,000</td>
                        </tr>
                        <tr>
                            <td>Gavin Joyce</td>
                            <td>Developer</td>
                            <td>Edinburgh</td>
                            <td>42</td>
                            <td>2010/12/22</td>
                            <td>$92,575</td>
                        </tr>
                        <tr>
                            <td>Jennifer Chang</td>
                            <td>Regional Director</td>
                            <td>Singapore</td>
                            <td>28</td>
                            <td>2010/11/14</td>
                            <td>$357,650</td>
                        </tr>
                        <tr>
                            <td>Brenden Wagner</td>
                            <td>Software Engineer</td>
                            <td>San Francisco</td>
                            <td>28</td>
                            <td>2011/06/07</td>
                            <td>$206,850</td>
                        </tr>
                        <tr>
                            <td>Fiona Green</td>
                            <td>Chief Operating Officer (COO)</td>
                            <td>San Francisco</td>
                            <td>48</td>
                            <td>2010/03/11</td>
                            <td>$850,000</td>
                        </tr>
                        <tr>
                            <td>Shou Itou</td>
                            <td>Regional Marketing</td>
                            <td>Tokyo</td>
                            <td>20</td>
                            <td>2011/08/14</td>
                            <td>$163,000</td>
                        </tr>
                        <tr>
                            <td>Michelle House</td>
                            <td>Integration Specialist</td>
                            <td>Sidney</td>
                            <td>37</td>
                            <td>2011/06/02</td>
                            <td>$95,400</td>
                        </tr>
                        <tr>
                            <td>Suki Burks</td>
                            <td>Developer</td>
                            <td>London</td>
                            <td>53</td>
                            <td>2009/10/22</td>
                            <td>$114,500</td>
                        </tr>
                        <tr>
                            <td>Prescott Bartlett</td>
                            <td>Technical Author</td>
                            <td>London</td>
                            <td>27</td>
                            <td>2011/05/07</td>
                            <td>$145,000</td>
                        </tr>
                        <tr>
                            <td>Gavin Cortez</td>
                            <td>Team Leader</td>
                            <td>San Francisco</td>
                            <td>22</td>
                            <td>2008/10/26</td>
                            <td>$235,500</td>
                        </tr>
                        <tr>
                            <td>Martena Mccray</td>
                            <td>Post-Sales support</td>
                            <td>Edinburgh</td>
                            <td>46</td>
                            <td>2011/03/09</td>
                            <td>$324,050</td>
                        </tr>
                        <tr>
                            <td>Unity Butler</td>
                            <td>Marketing Designer</td>
                            <td>San Francisco</td>
                            <td>47</td>
                            <td>2009/12/09</td>
                            <td>$85,675</td>
                        </tr>
                        <tr>
                            <td>Howard Hatfield</td>
                            <td>Office Manager</td>
                            <td>San Francisco</td>
                            <td>51</td>
                            <td>2008/12/16</td>
                            <td>$164,500</td>
                        </tr>
                        <tr>
                            <td>Hope Fuentes</td>
                            <td>Secretary</td>
                            <td>San Francisco</td>
                            <td>41</td>
                            <td>2010/02/12</td>
                            <td>$109,850</td>
                        </tr>
                        <tr>
                            <td>Vivian Harrell</td>
                            <td>Financial Controller</td>
                            <td>San Francisco</td>
                            <td>62</td>
                            <td>2009/02/14</td>
                            <td>$452,500</td>
                        </tr>
                        <tr>
                            <td>Timothy Mooney</td>
                            <td>Office Manager</td>
                            <td>London</td>
                            <td>37</td>
                            <td>2008/12/11</td>
                            <td>$136,200</td>
                        </tr>
                        <tr>
                            <td>Jackson Bradshaw</td>
                            <td>Director</td>
                            <td>New York</td>
                            <td>65</td>
                            <td>2008/09/26</td>
                            <td>$645,750</td>
                        </tr>
                        <tr>
                            <td>Olivia Liang</td>
                            <td>Support Engineer</td>
                            <td>Singapore</td>
                            <td>64</td>
                            <td>2011/02/03</td>
                            <td>$234,500</td>
                        </tr>
                        <tr>
                            <td>Bruno Nash</td>
                            <td>Software Engineer</td>
                            <td>London</td>
                            <td>38</td>
                            <td>2011/05/03</td>
                            <td>$163,500</td>
                        </tr>
                        <tr>
                            <td>Sakura Yamamoto</td>
                            <td>Support Engineer</td>
                            <td>Tokyo</td>
                            <td>37</td>
                            <td>2009/08/19</td>
                            <td>$139,575</td>
                        </tr>
                        <tr>
                            <td>Thor Walton</td>
                            <td>Developer</td>
                            <td>New York</td>
                            <td>61</td>
                            <td>2013/08/11</td>
                            <td>$98,540</td>
                        </tr>
                        <tr>
                            <td>Finn Camacho</td>
                            <td>Support Engineer</td>
                            <td>San Francisco</td>
                            <td>47</td>
                            <td>2009/07/07</td>
                            <td>$87,500</td>
                        </tr>
                        <tr>
                            <td>Serge Baldwin</td>
                            <td>Data Coordinator</td>
                            <td>Singapore</td>
                            <td>64</td>
                            <td>2012/04/09</td>
                            <td>$138,575</td>
                        </tr>
                        <tr>
                            <td>Zenaida Frank</td>
                            <td>Software Engineer</td>
                            <td>New York</td>
                            <td>63</td>
                            <td>2010/01/04</td>
                            <td>$125,250</td>
                        </tr>
                        <tr>
                            <td>Zorita Serrano</td>
                            <td>Software Engineer</td>
                            <td>San Francisco</td>
                            <td>56</td>
                            <td>2012/06/01</td>
                            <td>$115,000</td>
                        </tr>
                        <tr>
                            <td>Jennifer Acosta</td>
                            <td>Junior Javascript Developer</td>
                            <td>Edinburgh</td>
                            <td>43</td>
                            <td>2013/02/01</td>
                            <td>$75,650</td>
                        </tr>
                        <tr>
                            <td>Cara Stevens</td>
                            <td>Sales Assistant</td>
                            <td>New York</td>
                            <td>46</td>
                            <td>2011/12/06</td>
                            <td>$145,600</td>
                        </tr>
                        <tr>
                            <td>Hermione Butler</td>
                            <td>Regional Director</td>
                            <td>London</td>
                            <td>47</td>
                            <td>2011/03/21</td>
                            <td>$356,250</td>
                        </tr>
                        <tr>
                            <td>Lael Greer</td>
                            <td>Systems Administrator</td>
                            <td>London</td>
                            <td>21</td>
                            <td>2009/02/27</td>
                            <td>$103,500</td>
                        </tr>
                        <tr>
                            <td>Jonas Alexander</td>
                            <td>Developer</td>
                            <td>San Francisco</td>
                            <td>30</td>
                            <td>2010/07/14</td>
                            <td>$86,500</td>
                        </tr>
                        <tr>
                            <td>Shad Decker</td>
                            <td>Regional Director</td>
                            <td>Edinburgh</td>
                            <td>51</td>
                            <td>2008/11/13</td>
                            <td>$183,000</td>
                        </tr>
                        <tr>
                            <td>Michael Bruce</td>
                            <td>Javascript Developer</td>
                            <td>Singapore</td>
                            <td>29</td>
                            <td>2011/06/27</td>
                            <td>$183,000</td>
                        </tr>
                        <tr>
                            <td>Donna Snider</td>
                            <td>Customer Support</td>
                            <td>New York</td>
                            <td>27</td>
                            <td>2011/01/25</td>
                            <td>$112,000</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

```

[//]: # (Dashboard Ended)
<hr>

<center>Employee Management System</center>
<center>Project Completed!</center>

<hr>
