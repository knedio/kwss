@include('inc.header')
    <div id="wrapper">
        @if(session('account_type') == 'Customer')
            @include('inc.nav_customer')
        @else
            @include('inc.nav')
        @endif
        <div class="main">
            @yield('content')
        </div>
    </div>
    
    {{-- @include('modals.modals') --}}

@include('inc.footer')