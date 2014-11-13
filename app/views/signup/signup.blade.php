@extends('layouts.signupHeader')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="pull-left">
                            <h3 class="panel-title">Sign up</h3>
                        </div>
                        <div class="pull-right">
                            <span style="font-size: 11px;">
                                <a href="/signin" target="_self">Already have an account?</a>
                            </span>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if (null !== Session::get('error'))
                            <ul class="list_of_error" id="list_of_error_email">
                                <li id="error_item_email_default">
                                    {{ Session::get('error') }}
                                </li>
                            </ul>
                        @endif
                        <form role="form" action="/signup" name="signupForm" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ Input::old('email') }}" autofocus />
                                    @if ($errors->has('email'))
                                        <ul class="list_of_error" id="list_of_error_email">
                                            <li id="error_item_email_default">
                                                Please enter a valid email address
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Enter a password of 6 or more characters" name="password" type="password" value="" />
                                    @if ($errors->has('password'))
                                        <ul class="list_of_error" id="list_of_error_repassword">
                                            <li id="error_item_repassword_default">
                                                Please enter a password of 6 or more characters
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Confirm password" name="repassword" type="password" value="" />
                                    @if ($errors->has('repassword'))
                                        <ul class="list_of_error" id="list_of_error_repassword">
                                            <li id="error_item_repassword_default">
                                                Password does not match
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="label-checkbox inline">
                                        <input name="agree" type="checkbox" value="1" class="regular-checkbox chk-delete" />
                                    </label>
                                    I accept to the <a href="/terms" target="_blank">Terms of Service</a>
                                </div>
                                @if ($errors->has('agree'))
                                    <ul class="list_of_error" id="list_of_error_agree">
                                        <li id="error_item_agree_default">
                                            In order to use Schooler, you must agree to the Terms of Service.
                                        </li>
                                    </ul>
                                @endif
                                <div class="seperator"></div>
                                <button type="submit" name="submit" class="btn btn-lg btn-success btn-block">Sign up</button>
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