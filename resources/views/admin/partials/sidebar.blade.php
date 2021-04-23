<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <ul class="app-menu">
        <li>
            <a class="app-menu__item {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Dashboard</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item {{ Route::currentRouteName() == 'admin.settings' ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                <i class="app-menu__icon fa fa-cogs"></i>
                <span class="app-menu__label">Settings</span>
            </a>
        </li>
        <li>
    <a class="app-menu__item {{ Route::currentRouteName() == 'admin.categories.index' ? 'active' : '' }}"
        href="{{ route('admin.categories.index') }}">
        <i class="app-menu__icon fa fa-tags"></i>
        <span class="app-menu__label">Categories</span>
    </a>
</li>
<li>
    <a class="app-menu__item {{ Route::currentRouteName() == 'admin.attributes.index' ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}">
        <i class="app-menu__icon fa fa-th"></i>
        <span class="app-menu__label">Attributes</span>
    </a>
</li>
<li>
    <a class="app-menu__item {{ Route::currentRouteName() == 'admin.brands.index' ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
        <i class="app-menu__icon fa fa-briefcase"></i>
        <span class="app-menu__label">Brands</span>
    </a>
</li>
<li>
            <a class="app-menu__item {{ Route::currentRouteName() == 'admin.products.index' ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <i class="app-menu__icon fa fa-shopping-bag"></i>
                <span class="app-menu__label">Products</span>
            </a>
        </li>
        <li>
    <a class="app-menu__item {{ Route::currentRouteName() == 'admin.orders.index' ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
        <i class="app-menu__icon fa fa-bar-chart"></i>
        <span class="app-menu__label">Orders</span>
    </a>

	<li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview"
            ><i class="app-menu__icon fa fa-edit"></i
            ><span class="app-menu__label">Forms</span
            ><i class="treeview-indicator fa fa-angle-right"></i
          ></a>
          <ul class="treeview-menu">
            <li>
              <a class="treeview-item" href="form-components.html"
                ><i class="icon fa fa-circle-o"></i> Form Components</a
              >
            </li>
            <li>
              <a class="treeview-item" href="form-custom.html"
                ><i class="icon fa fa-circle-o"></i> Custom Components</a
              >
            </li>
            <li>
              <a class="treeview-item" href="form-samples.html"
                ><i class="icon fa fa-circle-o"></i> Form Samples</a
              >
            </li>
            <li>
              <a class="treeview-item" href="form-notifications.html"
                ><i class="icon fa fa-circle-o"></i> Form Notifications</a
              >
            </li>
          </ul>
        </li>
		
    <li  class="has-sub ">
					<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#orders"
						aria-expanded="false" aria-controls="orders">
						<i class="mdi mdi-cart-outline"></i>
						<span class="nav-text">Orders</span> <b class="caret"></b>
					</a>
    <ul class="collapse "  id="orders"
						data-parent="#sidebar-menu">
						<div class="sub-menu">
							<li  class="" >
								<a class="sidenav-item-link" href="{{ url('admin/orders')}}">
								<span class="nav-text">Orders</span>
								</a>
							</li>
							<li class="">
								<a class="sidenav-item-link" href="{{ url('admin/orders/trashed')}}">
								<span class="nav-text">Trashed</span>
								</a>
							</li>
							<li class="">
								<a class="sidenav-item-link" href="{{ url('admin/shipments')}}">
								<span class="nav-text">Shipments</span>
								</a>
							</li>
						</div>
					</ul>
</li>
<li  class="has-sub ">
					<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#report"
						aria-expanded="false" aria-controls="report">
						<i class="mdi mdi-signal-cellular-outline"></i>
						<span class="nav-text">Reports</span> <b class="caret"></b>
					</a>
					<ul class="collapse "  id="report"
						data-parent="#sidebar-menu">
						<div class="sub-menu">
							<li  class="" >
								<a class="sidenav-item-link" href="{{ url('admin/reports/revenue')}}">
								<span class="nav-text">Revenue</span>
								</a>
							</li>
							<li  class="" >
								<a class="sidenav-item-link" href="{{ url('admin/reports/product')}}">
								<span class="nav-text">Products</span>
								</a>
							</li>
							<li  class="" >
								<a class="sidenav-item-link" href="{{ url('admin/reports/inventory')}}">
								<span class="nav-text">Inventories</span>
								</a>
							</li>
							<li  class="" >
								<a class="sidenav-item-link" href="{{ url('admin/reports/payment')}}">
								<span class="nav-text">Payments</span>
								</a>
							</li>
						</div>
					</ul>
				</li>
    </ul>
</aside>