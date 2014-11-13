@extends('layouts.adminHeader')

@include('layouts.leftsidemenu')

@section('content')
        <div id="page-wrapper">
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">管理者を追加</div>
                        <div class="panel-body">
                            <div class="row">
                                <form name="addAdminForm" action="/admin/add" method="post" role="form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email" value="{{ Input::old('email') }}" />
                                            @if ($errors->has('email'))
                                                <ul class="list_of_error" id="list_of_error_email">
                                                    <li id="error_item_email_default">メールアドレスを入力してください。
                                                    </li>
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="text" class="form-control" name="password" value="" />
                                            @if ($errors->has('password'))
                                                <ul class="list_of_error" id="list_of_error_password">
                                                    <li id="error_item_password_default">パスワードを入力してください (5から32文字)。</li>
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-default">&nbsp;&nbsp;Add&nbsp;&nbsp;</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop