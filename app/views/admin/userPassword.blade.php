@extends('layouts.adminHeader')

@include('layouts.leftsidemenu')

@section('content')
        <div id="page-wrapper">
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">パスワード更新</div>
                        <div class="panel-body">
                            <div class="row">
                                <form name="updateUserPasswordForm" action="/admin/user/update/password/{{ $userId }}" method="post" role="form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" class="form-control" name="password" value="" />
                                            @if ($errors->has('password'))
                                                <ul class="list_of_error" id="list_of_error_password">
                                                    <li id="error_item_password_default">パスワードを入力してください (5から32文字)。</li>
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Re-Type New Password</label>
                                            <input type="password" class="form-control" name="repassword" value="" />
                                            @if ($errors->has('repassword'))
                                                <ul class="list_of_error" id="list_of_error_repassword">
                                                    <li id="error_item_repassword_default">パスワードは同じではありません。</li>
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-default">&nbsp;&nbsp;Update&nbsp;&nbsp;</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop