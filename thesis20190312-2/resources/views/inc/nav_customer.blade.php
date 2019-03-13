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
                <p>Welcome {{ session('lastname') }}!</p>
                <li>
                    <a href="{{ route('customer-home') }}" class="">
                        <i class="fa fa-fw fa-dashboard"></i>Dashboard
                    </a>
                </li>
                <li><a href="{{ route('user-profile',['account_type'=>session('account_type'),'id'=>session('user_id')]) }}" class=""><i class="lnr lnr-user"></i>Profile</a></li>
                <li><a href="{{ route('logout') }}" class=""><i class="fa fa-sign-out" ></i> Log Out</a></li>
                <br>
                <br>
            </ul>
        </nav>
    </div>
</div>
<!-- LEFT SIDEBAR -->