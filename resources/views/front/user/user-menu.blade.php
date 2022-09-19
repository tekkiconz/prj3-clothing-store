<div class="profile bg-shadow xs-mb15">
    <div class="user-avatar">
        <!-- start avatar -->
        <a href="{{ route('user.profile') }}" title="{{ Auth::user()->name }}">
            @if(Auth::user()->avatar !== 'default_avatar.png')
                <x-cld-image public-id="{{ Auth::user()->avatar }}"
                             width="256"
                             loading="lazy"
                             class="img-fluid" alt="{{ Auth::user()->name }}"></x-cld-image>
            @else
                <img src="{{ asset('images/default_avatar.png') }}" class="img-fluid" alt="{{ Auth::user()->name }}">
            @endif
        </a>
        <p>{{ Auth::user()->name }}</p>
        <!--end avatar -->
    </div>
    <nav>
        <ul>
            <li @if(Route::is('user.profile')) class="active" @endif><a href="{{ route('user.profile') }}"> <i
                        class='lni lni-alarm-clock'></i> Dashboard</a></li>

            <li @if(Route::is('user.information')) class="active" @endif><a href="{{ route('user.information') }}"><i
                        class='lni lni-user'></i> Information Update </a></li>

            <li @if(Route::is('user.order')) class="active" @endif><a href="{{ route('user.order') }}"> <i
                        class='lni lni-menu'></i> Order History</a></li>

            <li @if(Route::is('user.change.password')) class="active" @endif><a
                    href="{{ route('user.change.password')}}"> <i class='lni lni-pencil-alt'></i> Change Password</a>
            </li>

            <li><a href="{{ route('user.logout') }}"> <i class='lni lni-lock'></i> Signout</a></li>
        </ul>
    </nav>
</div>
