<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ url('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">BNC CRM</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <ul class="metismenu" id="menu">
        <li @if (Route::is('client.*')) class="mm-active" @endif>
            <a class="has-arrow" href="{{ route('client.index') }}">
                <div class="parent-icon"><i class='bx bx-user'></i>
                </div>
                <div class="menu-title">Leads</div>
            </a>
            <ul>
                <li> <a href="{{ route('client.index') }}"><i class="bx bx-user-check"></i>Lead List</a>
                </li>
                @if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Desk1 Leader' || Auth::user()->role->name == 'Desk2 Leader')
                    <li> <a href="{{ route('client.create') }}"><i class="bx bx-user-check"></i>New Lead</a>
                    </li>
                @endif
            </ul>
        </li>
        @if (Auth::user()->role->name == 'Admin')
            <li @if (Route::is('user.*')) class="mm-active" @endif>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-user'></i>
                    </div>
                    <div class="menu-title">Users</div>
                </a>
                <ul>
                    @if (Auth::user()->role->name == 'Admin')
                        <li> <a href="{{ route('user.index') }}"><i class="bx bx-user-check"></i>User List</a>
                        </li>
                    @endif
                    @if (Auth::user()->role->name == 'Admin')
                        <li> <a href="{{ route('user.create') }}"><i class="bx bx-user-plus"></i>New User</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Desk1 Leader' || Auth::user()->role->name == 'Desk2 Leader')
            <li @if (Route::is('team.*')) class="mm-active" @endif>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-user-pin'></i>
                    </div>
                    <div class="menu-title">Team</div>
                </a>
                <ul>
                    <li> <a href="{{ route('team.index') }}"><i class="bx bx-user-check"></i>Team List</a>
                    </li>
                    <li> <a href="{{ route('team.create') }}"><i class="bx bx-user-plus"></i>New Team</a>
                    </li>
                </ul>
            </li>
        @endif
        @if (Auth::user()->role->name == 'Admin')
            <li @if (Route::is('role.*')) class="mm-active" @endif>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-sitemap'></i>
                    </div>
                    <div class="menu-title">Roles</div>
                </a>
                <ul>
                    <li> <a href="{{ route('role.index') }}"><i class="bx bx-check-shield"></i>Role List</a>
                    </li>
                    <li> <a href="{{ route('role.create') }}"><i class="bx bx-plus-medical"></i>New Role</a>
                    </li>
                </ul>
            </li>
            {{-- <li @if (Route::is('messages.*')) class="mm-active" @endif>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-message'></i>
                    </div>
                    <div class="menu-title">Messages</div>
                </a>
                <ul>
                    <li> <a href="{{ route('messages.create') }}"><i class="bx bx-message-add"></i>New Message</a>
                    </li>
                    <li> <a href="{{ route('messages.index') }}"><i class="bx bx-message-detail"></i>Received Messages</a>
                    </li>
                    <li> <a href="{{ route('messages.sent') }}"><i class="bx bx-message-check"></i>Sent Messages</a>
                    </li>
                    <li> <a href="{{ route('messages.archive') }}"><i class="bx bx-message-minus"></i>Archive Messages</a>
                    </li>
                </ul>
            </li> --}}
            <li @if (Route::is('reports.*')) class="mm-active" @endif>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-notepad'></i>
                    </div>
                    <div class="menu-title">Reports</div>
                </a>
                <ul>
                    <li> <a href="{{ route('reports.index') }}"><i class="bx bx-receipt"></i>Lead reports</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('settings.index') }}">
                    <div class="parent-icon"><i class="bx bx-cog bx-spin"></i>
                    </div>
                    <div class="menu-title">Settings</div>
                </a>
            </li>
        @endif
        {{-- <li>
            <a href="{{ url('user-profile') }}">
                <div class="parent-icon"><i class="bx bx-user-circle"></i>
                </div>
                <div class="menu-title">User Profile</div>
            </a>
        </li> --}}
        <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form2').submit();">
                <div class="parent-icon"><i class="bx bx-log-out"></i>
                </div>
                <div class="menu-title">Logout</div>
            </a>
            <form id="logout-form2" action="{{ route('logout') }}" method="POST" class="hidden">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
</div>