<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion" style="background-color: #143D60" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">User Management</div>

                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Dashboard
                </a>
                @hasanyrole('Administrator')
                    <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                        Employee Accounts
                    </a>
                @endhasanyrole
                @hasanyrole('Doctor|Medical Staff')
                    <a class="nav-link {{ request()->routeIs('patient.index') ? 'active' : '' }}" href="{{ route('patient.index') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                        Patient Records
                    </a>
                @endhasanyrole
                <a class="nav-link {{ request()->routeIs('appointment.index') ? 'active' : '' }}" href="{{ route('appointment.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                    Appointment Records
                </a>
                @hasanyrole('Doctor|Medical Staff')
                    <a class="nav-link {{ request()->routeIs('medical-certificate.index') ? 'active' : '' }}" href="{{ route('medical-certificate.index') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                        Medical Certificate Records
                    </a>
                @endhasanyrole
                @hasanyrole('Administrator|Medical Staff')
                    <a class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}" href="{{ route('product.index') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                        Product Records
                    </a>
                @endhasanyrole
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ auth()->user()->roles->pluck('name')[0] }}
        </div>
    </nav>
</div>
