<div id="loading">
    <div id="loading-center">
    </div>
</div>
<!-- loader END -->
<!-- Wrapper Start -->
<div class="wrapper">
    <!-- Sidebar  -->
    <div class="iq-sidebar">
        <div class="iq-sidebar-logo d-flex justify-content-between">
            <a href="{{ route('dashboard.index') }}">
                <span class="logo-text">Creative Mind Task</span>
            </a>
            <div class="iq-menu-bt-sidebar">
                <div class="iq-menu-bt align-self-center">
                    <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                        <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="sidebar-scrollbar">
            <nav class="iq-sidebar-menu">
                <ul id="iq-sidebar-toggle" class="iq-menu">
                    <li class="iq-menu-title"><i
                            class="ri-subtract-line"></i><span>{{ ___('General Management') }}</span></li>
                    {{-- Users --}}
                    <li class="{{ isActiveRoute('users.*') }}">
                        <a href="#users" class="iq-waves-effect collapsed" data-toggle="collapse"
                            aria-expanded="false"><i class="ri-user-line"></i><span>{{ ___('Manage Users') }}</span><i
                                class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="users" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                            <li class="{{ isActiveRoute('users.create') }}"><a href="{{ route('users.create') }}"><i
                                        class="ri-user-add-line"></i>{{ ___('Add Users') }}</a>
                            </li>
                            <li class="{{ isActiveRoute('users.index') }}"><a href="{{ route('users.index') }}"><i
                                        class="ri-file-list-line"></i>{{ ___('Users List') }}</a></li>
                        </ul>
                    </li>

                </ul>
            </nav>
            <div class="p-3"></div>
        </div>
    </div>
    <!-- TOP Nav Bar -->
    <div class="iq-top-navbar">
        <div class="iq-navbar-custom">
            <div class="iq-sidebar-logo">
                <div class="top-logo">
                    <a href="../index.html" class="logo">
                        <img src="../images/logo.gif" class="img-fluid" alt="">
                        <span>Creative Mind Task</span>
                    </a>
                </div>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>
                <div class="iq-menu-bt align-self-center">
                    <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                        <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                    </div>
                </div>

                {{-- <ul class="navbar-list">

                    <li>
                        <a href="#"
                            class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">
                            <img src="{{ getImageUrl(auth('staff_users')->user()->staff_user_img) ?? asset('assets/images/user/default_user.png') }}"
                                class="img-fluid rounded mr-3" alt="user">
                            <div class="caption">
                                <h6 class="mb-0 line-height text-white">{{ auth('staff_users')->user()->name }}</h6>

                            </div>
                        </a>
                        <div class="iq-sub-dropdown iq-user-dropdown">
                            <div class="iq-card shadow-none m-0">
                                <div class="iq-card-body p-0 ">
                                    <div class="bg-primary p-3">
                                        <h5 class="mb-0 text-white line-height">{{ ___('Hello') }}
                                            {{ auth('staff_users')->user()->name }}</h5>

                                    </div>

                                    <div class="d-inline-block w-100 text-center p-3">
                                        <form action="{{ route('logout') }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary dark-btn-primary">
                                                {{ ___('Sign out') }} <i class="ri-login-box-line ml-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul> --}}
            </nav>

        </div>
    </div>
