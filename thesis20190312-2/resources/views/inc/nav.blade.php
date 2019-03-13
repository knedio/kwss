<nav class="navbar navbar-fixed-top">
    <div class="navbar-btn">
        <button type="button" id="menu-toggle"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
</nav>
<!-- LEFT SIDEBAR -->
<div id="sidebar-nav" class="sidebar">
    <div class="scrollbar" id="style-1">
        <nav>
            <ul class="nav">
                <div class="logo-container" >
                    <img src="{{ asset('img/logo-2.png') }}" width="75%" class="center-block img-responsive" height="auto" alt="">
                </div>
                <p>Welcome Admin!</p>
                <li>
                    <a href="{{ route('dashboard') }}" class="">
                        <i class="fa fa-fw fa-dashboard"></i>Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('database-backup') }}" class="">
                        <i class="fa fa-database"></i>Database Backup
                    </a>
                </li>
                <li>
                    <a href="{{ route('user-profile',['account_type'=>session('account_type'),'id'=>session('user_id')]) }}" class="">
                        <i class="lnr lnr-user"></i>Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('request') }}" class="">
                        <i class="fa fa-list"></i>Request
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer-type') }}" class="">
                        <i class="lnr lnr-user"></i>Customer Type
                    </a>
                </li>
                <li>
                    <a href="#manage" data-toggle="collapse" class="collapsed"><i class="fa fa-tachometer"></i>Meter Reading<i class="icon-submenu lnr lnr-chevron-left"></i></a>
                    <div id="manage" class="collapse ">
                        <ul class="nav">
                            <li><a href="{{ route('meter-reading') }}" class="">All Readings</a></li>
                            <li><a href="{{ route('monthly-meter-reading') }}" class="">Monthly Readings</a></li>
                        </ul>
                    </div>
                </li>
                <li><a href="{{ route('payments') }}" class=""><i class="fa fa-money"></i>Payments</a></li>
                <li><a href="{{ route('users') }}" class=""><i class="lnr lnr-users"></i>Users</a></li>
                <li><a href="{{ route('sms') }}" class=""><i class="fa fa-cog"></i>SMS</a></li>
                <li>
                    <a href="uploads/thesis.apk" class="">
                        <i class="fa fa-download"></i>Download APK
                    </a>
                </li>
                <li><a href="{{ route('logout') }}" class=""><i class="fa fa-sign-out" ></i> Log Out</a></li>
                <br>
                <br>
            </ul>
        </nav>
    </div>
</div>

<!-- LEFT SIDEBAR -->