<div class="sidebar-container">
	<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

		<!-- Sidebar - Brand -->
		<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
			<div class="sidebar-brand-icon rotate-n-15">
				<i class="fas fa-laugh-wink"></i>
			</div>
		</a>

		<!-- Divider -->
		<hr class="sidebar-divider my-0">

		<!-- Nav Item - Dashboard -->
		<li class="nav-item">
			<a class="nav-link" href="{{ route('admin.dashboard') }}">
				<i class="fas fa-fw fa-tachometer-alt"></i>
				<span>Dashboard</span></a>
		</li>

		<!-- Divider -->
		<hr class="sidebar-divider">

		<!-- Heading -->
		<div class="sidebar-heading">
			Records
		</div>

		<!-- Nav Item - Pages Collapse Menu -->
		<li class="nav-item">
			<a class="nav-link" href="#" data-toggle="collapse" data-target="#collapesConfigure"
				aria-expanded="true" aria-controls="collapesConfigure">
				<i class="fas fa-fw fa-folder"></i>
				<span>Configure</span>
			</a>
			<div id="collapesConfigure" class="collapse" aria-labelledby="headingConfigurePages" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<h6 class="collapse-header">Assessment Centers</h6>
					<a class="collapse-item" href="{{ route('admin.provinces') }}">Provinces</a>
					<a class="collapse-item" href="{{ route('admin.schools') }}">Schools</a>
					<a class="collapse-item" href="{{ route('admin.teachers') }}">Teachers</a>
					<div class="collapse-divider"></div>
					<h6 class="collapse-header">Students</h6>
					<a class="collapse-item" href="{{ route('admin.pupils') }}">Pupils</a>
					<div class="collapse-divider"></div>
					<h6 class="collapse-header">Assessment</h6>
					<a class="collapse-item" href="{{ route('admin.tests') }}">Tests</a>
					<a class="collapse-item" href="{{ route('admin.questions') }}">Questions</a>
					<h6 class="collapse-header">Checklists</h6>
					<a class="collapse-item" href="{{ route('admin.categories') }}">Categories</a>
					<a class="collapse-item" href="{{ route('admin.checklists') }}">Checklists</a>
				</div>
			</div>
		</li>

		<!-- Divider -->
		<hr class="sidebar-divider">

		<!-- Heading -->
		<div class="sidebar-heading">
			Reports
		</div>

		<!-- Nav Item - Pages Collapse Menu -->
		<li class="nav-item">
			<a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseReports" aria-expanded="true"
				aria-controls="collapsePages">
				<i class="fa fa-fw fa-chart-pie"></i>
				<span>Progress Reports</span>
			</a>
			<div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<h6 class="collapse-header">Pupils</h6>
					<a class="collapse-item" href="#">View</a>
					<h6 class="collapse-header">Forms</h6>
					<a class="collapse-item" href="{{ route('admin.reports') }}">Intake Form</a>
				</div>
			</div>
		</li>

		<!-- Divider -->
		<hr class="sidebar-divider d-none d-md-block">

		<!-- Sidebar Toggler (Sidebar) -->
		{{-- <div class="text-center d-none d-md-inline">
			<button class="rounded-circle border-0" id="sidebarToggle"></button>
		</div> --}}

	</ul>
</div>
