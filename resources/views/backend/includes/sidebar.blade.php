<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard.index') }}" class="brand-link">
        <img src="{{ url('/assets/backend/img/company.png') }}" alt="{{ env('APP_NAME','Application') }}"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ env('APP_NAME','Application') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link {{ (request()->is('dashboard*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/products') }}" class="nav-link {{ (request()->is('products*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Product</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/product-categories') }}" class="nav-link {{ (request()->is('product-categories*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Product Category</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/purchases') }}" class="nav-link {{ (request()->is('purchases*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Purchase</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
