
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

{{--                    Notices --}}
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsNotice" aria-expanded="false" aria-controls="collapseLayoutsNotice">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Notice
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayoutsNotice" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">

                        <nav class="sb-sidenav-menu-nested nav">
                            @if(isset(auth()->user()->role->permission['name']['notice']['can-list']))
                                <a class="nav-link" href="{{route('notices.index')}}">All Notices</a>
                            @endif
                        </nav>
                        <nav class="sb-sidenav-menu-nested nav">
                            @if(isset(auth()->user()->role->permission['name']['notice']['can-add']))
                                <a class="nav-link" href="{{route('notices.create')}}">Create Notice</a>
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
