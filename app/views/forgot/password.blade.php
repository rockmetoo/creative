@extends('layouts.signinHeader')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="pull-left">
                            <h3 class="panel-title">Forgot password</h3>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if ($errors->has('email'))
                            <ul class="list_of_error" id="list_of_error_email">
                                <li id="error_item_email_default">Please enter your email address correctly</li>
                            </ul>
                        @endif
                        <form role="form" action="/forgot/password" name="forgotPasswordForm" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ Input::old('email') }}" autofocus>
                                </div>
                                <button type="submit" name="submit" class="btn btn-lg btn-success btn-block">&nbsp;&nbsp;Submit&nbsp;&nbsp;</button>
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