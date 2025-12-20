<!--aside open-->
<aside class="app-sidebar">
    <!-- <div class="app-sidebar__logo">
        <a class="header-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="full-logo">
        </a>
    </div> -->
    <div class="app-sidebar3">
        <!-- <div class="app-sidebar__user">
            <div class="dropdown user-pro-body text-center">
                <div class="side-bar-user rounded-circle">
                    <img src="{{ admin()->profile != null ? admin()->profile : asset('assets/images/profile/default.jpg') }}" alt="user-img" class="avatar-xxl ">
                </div>
                <div class="user-info">
                    <h5 class=" mb-0">{{ admin()->first_name }}</h5>
                </div>
            </div>
        </div> -->
        <ul class="side-menu">

            <li class="slide">
                <a class="side-menu__item {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span class="side-menu__label">Dashboard</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item {{ request()->segment(2) == 'users' ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i>
                    <span class="side-menu__label">Users</span>
                </a>
            </li>


            <!-- Product Management Dropdown -->
            <li class="slide">
                <a class="side-menu__item {{ in_array(request()->segment(2), ['products', 'product-variants', 'variant-options', 'categories']) ? 'active' : '' }}" 
                   data-toggle="slide" href="javascript:void(0)">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="side-menu__label">Product Management</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li>
                        <a href="{{ route('admin.products.index') }}" 
                           class="slide-item {{ request()->segment(2) == 'products' ? 'active' : '' }}">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.product-variants.index') }}" 
                           class="slide-item {{ request()->segment(2) == 'product-variants' ? 'active' : '' }}">
                            <i class="fas fa-tags"></i> Product Variants
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.variant-options.index') }}" 
                           class="slide-item {{ request()->segment(2) == 'variant-options' ? 'active' : '' }}">
                            <i class="fas fa-palette"></i> Variant Options
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="slide-item {{ request()->segment(2) == 'categories' ? 'active' : '' }}">
                            <i class="fas fa-list"></i> Categories
                        </a>
                    </li>
                </ul>
            </li>


            <li class="slide">
                <a class="side-menu__item {{ request()->segment(2) == 'addons' ? 'active' : '' }}"
                    href="{{ route('admin.addons.index') }}">
                    <i class="fas fa-puzzle-piece"></i>
                    <span class="side-menu__label">Addons</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item {{ request()->segment(2) == 'orders' ? 'active' : '' }}"
                    href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="side-menu__label">Orders</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item {{ request()->segment(2) == 'settings' ? 'active' : '' }}"
                    href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i>
                    <span class="side-menu__label">Settings</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
<!--aside closed-->
