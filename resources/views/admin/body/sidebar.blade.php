@php
    $currentRoute = \Route::current()->getName();
@endphp
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{asset('backend')}}/assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Admin</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{route('admin.dashboard')}}" class="">
                <div class="parent-icon"><i class='bx bx-cookie'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>

        <li>
            <a href="" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Category</div>
            </a>
            <ul>
            {{--  @if(Auth::user()->can('category.list'))  --}}

                <li> <a href="{{ route('category') }}"><i class="bx bx-right-arrow-alt {{ $currentRoute == 'category' ? 'active' : '' }}"></i>All Category</a>
                </li>
            {{--  @endif  --}}
            {{--  @if(Auth::user()->can('category.add'))  --}}
                <li> <a href="{{ route('subcategory') }}"><i class="bx bx-right-arrow-alt"></i>Subcategory</a>
                </li>
            {{--  @endif  --}}
            </ul>
        </li>





    </ul>
    <!--end navigation-->
</div>
