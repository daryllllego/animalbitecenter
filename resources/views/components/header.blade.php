@props(['title' => 'Page Title', 'role' => auth()->user()->position ?? 'User Role'])

<div class="header">
	<div class="header-content">
		<nav class="navbar navbar-expand">
			<div class="collapse navbar-collapse justify-content-between">
				<div class="header-left d-flex align-items-center">
					<div class="dashboard_bar" data-page-title>
						{{ $title }}
					</div>
                    <form action="{{ route('animal-bite.set-date') }}" method="POST" class="ms-4 d-flex align-items-center" id="global-filter-form">
                        @csrf
                        <div class="date-filter-wrapper me-4">
                            <label class="date-filter-label">SELECT DATE</label>
                            <div class="date-input-container">
                                <i class="fa fa-calendar-alt calendar-icon"></i>
                                <input type="date" name="selected_date" 
                                    value="{{ session('selected_date', date('Y-m-d')) }}"
                                    onchange="document.getElementById('global-filter-form').submit()"
                                    class="modern-date-input">
                            </div>
                        </div>

                        @if(auth()->user()->is_super_admin)
                        <div class="date-filter-wrapper">
                            <label class="date-filter-label">SELECT BRANCH</label>
                            <div class="date-input-container">
                                <i class="fa fa-map-marker-alt calendar-icon"></i>
                                <select name="selected_branch" onchange="document.getElementById('global-filter-form').submit()" class="modern-date-input" style="padding-right: 40px; -webkit-appearance: none;">
                                    <option value="All Branches" {{ session('selected_branch') == 'All Branches' ? 'selected' : '' }}>All Branches</option>
                                    @php
                                        $branches = [
                                            'Mandaue Branch', 'Lapu-Lapu Branch', 'Balamban Branch', 'Talisay Branch', 
                                            'Bogo Branch', 'Tubigon Branch', 'Guadalupe Branch', 'Inabanga Branch', 
                                            'Tagbilaran Branch', 'Talibon Branch', 'Camotes Branch', 'Consolacion Branch', 
                                            'Carmen Branch', 'Panglao Branch', 'Liloan Branch', 'Jagna Branch', 'Ubay Branch'
                                        ];
                                    @endphp
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch }}" {{ session('selected_branch') == $branch ? 'selected' : '' }}>{{ $branch }}</option>
                                    @endforeach
                                </select>
                                <i class="fa fa-chevron-down" style="position: absolute; right: 12px; color: #2953e8; pointer-events: none;"></i>
                            </div>
                        </div>
                        @endif
                    </form>
				</div>
                <style>
                    .date-filter-wrapper {
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                    }
                    .date-filter-label {
                        font-size: 12px;
                        font-weight: 800;
                        color: #2953e8;
                        letter-spacing: 1px;
                        margin-bottom: 4px;
                        margin-left: 2px;
                    }
                    .date-input-container {
                        position: relative;
                        display: flex;
                        align-items: center;
                    }
                    .calendar-icon {
                        position: absolute;
                        left: 12px;
                        color: #2953e8;
                        font-size: 16px;
                        pointer-events: none;
                    }
                    .modern-date-input {
                        padding: 10px 15px 10px 40px;
                        border-radius: 10px;
                        border: 2px solid #e2e8f0;
                        font-size: 15px;
                        font-weight: 700;
                        color: #1e293b;
                        background-color: #f8fafc;
                        transition: all 0.3s ease;
                        cursor: pointer;
                        outline: none;
                    }
                    .modern-date-input:hover {
                        border-color: #2953e8;
                        background-color: white;
                        box-shadow: 0 4px 12px rgba(41, 83, 232, 0.1);
                    }
                    .modern-date-input:focus {
                        border-color: #2953e8;
                        background-color: white;
                        box-shadow: 0 0 0 4px rgba(41, 83, 232, 0.1);
                    }
                </style>
				<ul class="navbar-nav header-right">
					<li class="nav-item dropdown header-profile">
						<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" data-toggle="dropdown">
							<div class="header-info">
								<span>{{ auth()->user()->name }}</span>
								<small data-user-role>{{ auth()->user()->position }}</small>
							</div>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a href="{{ route('profile') }}" class="dropdown-item ai-icon">
								<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
									height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
									<circle cx="12" cy="7" r="4"></circle>
								</svg>
								<span class="ms-2">Change pass </span>
							</a>
							

							<div class="dropdown-divider"></div>
							
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
							<a href="#" class="dropdown-item ai-icon" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18"
									height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
									<polyline points="16 17 21 12 16 7"></polyline>
									<line x1="21" y1="12" x2="9" y2="12"></line>
								</svg>
								<span class="ms-2">Logout </span>
							</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</div>
