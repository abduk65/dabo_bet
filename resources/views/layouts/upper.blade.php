<div class="position-relative iq-banner">
    <!--Nav Start-->
    <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
        <div class="container-fluid navbar-inner">
            <a href="../dashboard/index.html" class="navbar-brand">

                <!--Logo start-->
                <div class="logo-main">
                    <div class="logo-normal">

                    </div>
                    <div class="logo-mini">

                    </div>
                </div>
                <!--logo End-->




                <h4 class="logo-title">Hope UI</h4>
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">

                </i>
            </div>
            <div class="input-group search-input">
                <span class="input-group-text" id="search-input">

                </span>
                <input type="search" class="form-control" placeholder="Search...">
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <span class="mt-2 navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">

                    <li class="nav-item dropdown">


                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link" id="notification-drop" data-bs-toggle="dropdown">

                            <span class="bg-danger dots"></span>
                        </a>
                        <div class="p-0 sub-drop dropdown-menu dropdown-menu-end" aria-labelledby="notification-drop">
                            <div class="m-0 shadow-none card">
                                <div class="py-3 card-header d-flex justify-content-between bg-primary">
                                    <div class="header-title">
                                        <h5 class="mb-0 text-white">All Notifications</h5>
                                    </div>
                                </div>
                                <div class="p-0 card-body">
                                    <a href="#" class="iq-sub-card">
                                        <div class="d-flex align-items-center">

                                            <div class="ms-3 w-100">
                                                <h6 class="mb-0 ">Emma Watson Bni</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0">95 MB</p>
                                                    <small class="float-end font-size-12">Just Now</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link" id="mail-drop" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">

                            <span class="bg-primary count-mail"></span>
                        </a>
                        <div class="p-0 sub-drop dropdown-menu dropdown-menu-end" aria-labelledby="mail-drop">
                            <div class="m-0 shadow-none card">
                                <div class="py-3 card-header d-flex justify-content-between bg-primary">
                                    <div class="header-title">
                                        <h5 class="mb-0 text-white">All Message</h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            <div class="caption ms-3 d-none d-md-block ">
                                <h6 class="mb-0 caption-title">Austin Robertson</h6>
                                <p class="mb-0 caption-sub-title">Marketing Administrator</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../dashboard/app/user-profile.html">Profile</a></li>
                            <li><a class="dropdown-item" href="../dashboard/app/user-privacy-setting.html">Privacy
                                    Setting</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="post">@csrf<button type="submit"
                                        class="dropdown-item">Logout</button></form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav> <!-- Nav Header Component Start -->
    <div class="iq-navbar-header" style="height: 215px;">
        <div class="container-fluid iq-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="flex-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h1>Hello Devs!</h1>
                            <p>We are on a mission to help developers like you build successful projects for FREE.</p>
                        </div>
                        <div>
                            <a href="" class="btn btn-link btn-soft-light">

                                Announcements
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- Nav Header Component End -->
    <!--Nav End-->
</div>
