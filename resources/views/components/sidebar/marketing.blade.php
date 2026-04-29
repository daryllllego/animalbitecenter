<nav class="modern-nav-menu">
	<!-- Dashboard -->
	<a href="{{ route('marketing.dashboard') }}" class="modern-nav-item {{ request()->routeIs('marketing.dashboard') ? 'active' : '' }}" data-page="dashboard">
		<div class="modern-nav-icon">
			<i class="flaticon-381-home-2"></i>
		</div>
		<span class="modern-nav-label">Dashboard</span>
	</a>

	<!-- POS -->
	<a href="{{ route('marketing.pos.sale') }}" class="modern-nav-item {{ request()->routeIs('marketing.pos.sale') ? 'active' : '' }}" data-page="pos">
		<div class="modern-nav-icon">
			<i class="las la-store"></i>
		</div>
		<span class="modern-nav-label">POS</span>
	</a>

	<!-- Inventory -->
	<a href="{{ route('marketing.products') }}" class="modern-nav-item {{ request()->routeIs('marketing.products') ? 'active' : '' }}" data-page="inventory">
		<div class="modern-nav-icon">
			<i class="las la-boxes"></i>
		</div>
		<span class="modern-nav-label">Inventory</span>
	</a>

	<!-- Invoice -->
	<a href="{{ route('marketing.sales-invoice') }}" class="modern-nav-item {{ request()->routeIs('marketing.sales-invoice') ? 'active' : '' }}" data-page="invoice">
		<div class="modern-nav-icon">
			<i class="las la-file-invoice-dollar"></i>
		</div>
		<span class="modern-nav-label">Invoice</span>
	</a>

    <!-- POS Settings -->
	<a href="{{ route('marketing.pos.settings') }}" class="modern-nav-item {{ request()->routeIs('marketing.pos.settings') ? 'active' : '' }}" data-page="pos-settings">
		<div class="modern-nav-icon">
			<i class="las la-cog"></i>
		</div>
		<span class="modern-nav-label">POS Settings</span>
	</a>
</nav>
