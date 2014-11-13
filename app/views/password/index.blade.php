@extends('layouts.studentHeader')

@include('layouts.leftsidemenu')

@include('layouts.leftSideUserBlock')

@section('internalJSLibrary')
@stop

@section('internalJSCode')
@stop

@section('content')
    <div id="page-wrapper">
        <br/>
        <div class="row rowContainer">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Change password</div>
                    <div class="panel-body">
                        @if (null !== Session::get('error'))
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <div class="row">
                            <form name="changePasswordForm" action="/change/password" method="post" role="form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Current password</label>
                                        <input type="password" class="form-control" name="currentPassword" />
                                        @if ($errors->has('currentPassword'))
                                            <ul id="list_of_error_currentPassword" class="list_of_error">
                                                <li id="error_item_currentPassword_default">Please enter your current password</li>
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>New password</label>
                                        <input type="password" class="form-control" name="newPassword" />
                                        @if ($errors->has('newPassword'))
                                            <ul id="list_of_error_newPassword" class="list_of_error">
                                                <li id="error_item_newPassword_default">Please enter a password of 6 or more characters</li>
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Re-new password</label>
                                        <input type="password" class="form-control" name="renewPassword" />
                                        @if ($errors->has('renewPassword'))
                                            <ul id="list_of_error_renewPassword" class="list_of_error">
                                                <li id="error_item_renewPassword_default">Password does not match</li>
                                            </ul>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-default">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop