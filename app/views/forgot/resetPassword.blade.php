@extends('layouts.signinHeader')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="pull-left">
                            <h3 class="panel-title">Reset Your Password</h3>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if ($errors->has('code'))
                            <ul id="list_of_error_code" class="list_of_error">
                                <li id="error_item_code_default">
                                    Invalid link. If you would like to reset password please click <a href="/forgot/password" target="_self">here</a>
                                </li>
                            </ul>
                        @endif
                        <form role="form" action="/forgot/password/verify" name="forgotPasswordForm" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="code" value="{{ Input::get('code') }}">
                            <fieldset>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="newPassword" placeholder="Enter new password" />
                                    @if ($errors->has('newPassword'))
                                        <ul id="list_of_error_newPassword" class="list_of_error">
                                            <li id="error_item_newPassword_default">Please enter a password of 6 or more characters</li>
                                        </ul>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="renewPassword" placeholder="Re-enter new password" />
                                    @if ($errors->has('renewPassword'))
                                        <ul id="list_of_error_renewPassword" class="list_of_error">
                                            <li id="error_item_renewPassword_default">Password does not match</li>
                                        </ul>
                                    @endif
                                </div>
                                <button type="submit" name="submit" class="btn btn-lg btn-success btn-block">Reset Your Password</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

