@if (Auth::user()->userType == 1 && Auth::user()->userStatus == 1)
    @section('leftSideUserBlock')
        @if ($USER_PROFILE['p1'])
            <img src="@if (App::environment('production')){{ secure_url('/pf/p1/') }}@else{{ URL::to('/pf/p1/') }}@endif" alt="{{ $USER_PROFILE['fullName'] }}"></img>
        @else
            <img src="@if (App::environment('production')){{ secure_url('/images/anonymous.png') }}@else{{ URL::to('/images/anonymous.png') }}@endif" alt="{{ $USER_PROFILE['fullName'] }}"></img>
        @endif
        <div class="detail">
            <strong>{{ $USER_PROFILE['fullName'] }}</strong>
            <ul class="list-inline">
                <li class="">
                    <a href="/profile">Profile</a>
                </li>
                <li class="">
                    <a href="/messages" class="no-margin">Messages</a>
                </li>
            </ul>
        </div>
    @stop
@endif

