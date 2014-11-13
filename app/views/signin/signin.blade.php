@extends('layouts.signinHeader')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="pull-left">
                            <h3 class="panel-title">Signin</h3>
                        </div>
                        <div class="pull-right">
                            <span style="font-size: 11px;">
                                <a href="/signup" target="_self">Don't have any account?</a>
                            </span>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if ($errors->has('email') || Session::get('loginFailure'))
                            <ul class="list_of_error" id="list_of_error_email">
                                <li id="error_item_email_default">Please enter your email address and password correctly</li>
                            </ul>
                        @endif
                        <form role="form" action="/signin" name="signinForm" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ Input::old('email') }}" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <label class="label-checkbox inline">
                                        <input name="remember" type="checkbox" value="1" class="regular-checkbox chk-delete" />
                                    </label>
                                    Remember me
                                </div>
                                <div class="form-group">
                                    <a href="/forgot/password" target="_self">Forgot your password?</a>
                                </div>
                                <button type="submit" name="submit" class="btn btn-lg btn-success btn-block">Sign in</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
@stop