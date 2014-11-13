@if (Auth::user()->userType == 1 && Auth::user()->userStatus == 1)
    @section('leftSideMenu')
        <li>
            <a href="/dashboard" @if (Route::getCurrentRoute()->getPath() == 'dashboard') class="active" @endif><i class="fa dashboard-icon fa-fw"></i>&nbsp;&nbsp;Dashboard</a>
        </li>
        <li>
            <a href="/questions" @if (Route::getCurrentRoute()->getPath() == 'questions') class="active" @endif><i class="fa question-icon fa-fw"></i>&nbsp;&nbsp;Questions</a>
        </li>
        <li>
            <a href="/lessons" @if (Route::getCurrentRoute()->getPath() == 'lessons') class="active" @endif><i class="fa lesson-icon fa-fw"></i>&nbsp;&nbsp;Lessons</a>
        </li>
        <li @if (Route::getCurrentRoute()->getPath() == 'schools') class="active" @endif>
            <a href="#">
                <i class="fa fa-fw school-icon"></i>&nbsp;&nbsp;Schools<span class="fa arrow"></span>
            </a>
            <!-- <ul class="nav nav-second-level">
                <li>
                    <a href="/familyso/order" @if (Route::getCurrentRoute()->getPath() == 'familyso/order') class="active" @endif>受注状況</a>
                </li>
            </ul> -->
        </li>
    @stop
@endif

