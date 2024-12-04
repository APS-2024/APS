<!-- Side-Bar -->

@php 
use Illuminate\Support\Facades\Auth;

@endphp
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 fixed-start" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="" target="_blank">
            <span class="ms-1 font-weight-bold">Financial Management System</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">

    @php 

$user= Auth::user();
  $role=  $user->hasRole('Client');
    @endphp
@if($role == true)

<li class="nav-item">
                <a class="nav-link {{ (Request::is('user/dashboard','user/dashboard/*') ? 'active' : '') }}" href="{{ route('dashboard') }}">
                    <div class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class=" fa fa-home" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            @can('User access')
            <li class="nav-item">
                <a class="nav-link  {{ (Request::is('user/profile','user/profile/*') ? 'active' : '') }}" href="{{ route('profile.index') }}">
                <div class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class=" fa fa-list" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Client</span>
                </a>
            </li>

          @endcan  
        

@else



            <li class="nav-item">
                <a class="nav-link {{ (Request::is('admin') ? 'active' : '') }}" href="{{ route('admin.dashboard') }}">
                    <div class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class=" fa fa-home" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            

            <li class="nav-item">
                <a class="nav-link {{ (Request::is('admin/users','admin/users/*') ? 'active' : '') }} " href="{{ route('admin.users.index') }}">
                <div class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class=" fa fa-users" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Client</span>
                </a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link {{ (Request::is('admin/permissions','admin/permissions/*') ? 'active' : '') }} " href="{{ route('admin.permissions.index') }}">
                <div class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class=" fa fa-users" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Permissions</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ (Request::is('admin/roles','admin/roles/*') ? 'active' : '') }} " href="{{ route('admin.roles.index') }}">
                <div class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class=" fa fa-users" aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Role</span>
                </a>
            </li>
        
           @endif
        </ul>
    </div>
</aside>
<!-- End of Side-Bar -->
