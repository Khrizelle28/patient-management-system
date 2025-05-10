<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">User Management</div>
                <a class="nav-link" href="{{ route('admin.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Employee Accounts
                </a>
                <a class="nav-link" href="{{ route('patient.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Patient Records
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{-- {{ dd(auth()->user()->roles) }} --}}
            {{ auth()->user()->roles->pluck('name')[0] }}
        </div>
    </nav>
</div>