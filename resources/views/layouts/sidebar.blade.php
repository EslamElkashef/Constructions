<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <ul class="navbar-nav" id="navbar-nav">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{ route('employees.index') }}" class="nav-link">@lang('translation.employees')</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('clients.index') }}" class="nav-link">@lang('translation.clients')</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('team.index') }}" class="nav-link">@lang('translation.team')</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('salaries.index') }}" class="nav-link">@lang('translation.salaries')</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('stock.index') }}" class="nav-link">@lang('translation.stock')</a>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarExpenses" class="nav-link" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarExpenses">@lang('translation.general-expenses')
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarExpenses">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('general-expenses.index') }}"
                                        class="nav-link">@lang('translation.general-expenses')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('general-expense-report.index') }}"
                                        class="nav-link">@lang('translation.general-expenses-report')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('general-expense-categories.index') }}"
                                        class="nav-link">@lang('translation.general-expenses-categories')</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarRevenues" class="nav-link" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarRevenues">@lang('translation.general-revenues')
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarRevenues">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('general-revenues.index') }}"
                                        class="nav-link">@lang('translation.general-revenues')</a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="{{ route('general-revenues-categories.index') }}"
                                        class="nav-link">@lang('translation.general-revenues-categories')</a>
                                </li> --}}
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('calendar.index') }}" class="nav-link">@lang('translation.calendar')</a>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarProjects" class="nav-link" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProjects">@lang('translation.projects')
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarProjects">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('projects.index') }}" class="nav-link">@lang('translation.list-project')</a>
                                </li>

                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarTasks" class="nav-link" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarTasks">@lang('translation.tasks')
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarTasks">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('tasks.index') }}" class="nav-link">@lang('translation.list-tasks')</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarUnits" class="nav-link" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarUnits">@lang('translation.units')
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarUnits">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('units.index') }}" class="nav-link">@lang('translation.units')</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarReports" class="nav-link" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarReports">@lang('translation.reports')
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarReports">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('units.reports.index') }}"
                                        class="nav-link">@lang('translation.reports')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('units.reports.realestate') }}"
                                        class="nav-link">@lang('translation.real_estate')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('units.reports.sales') }}"
                                        class="nav-link">@lang('translation.sales')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('units.reports.construction') }}"
                                        class="nav-link">@lang('translation.construction')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('units.reports.finance') }}"
                                        class="nav-link">@lang('translation.finance')</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
