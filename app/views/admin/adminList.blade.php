@extends('layouts.adminHeader')

@include('layouts.leftsidemenu')

@section('dataTableCSS')
    @if (App::environment('production'))
        {{ HTML::style('/css/plugins/dataTables.bootstrap.css', [], true) }}
        {{ HTML::style('/css/alertify.core.css', [], true) }}
        {{ HTML::style('/css/alertify.default.css', [], true) }}
    @else
        {{ HTML::style('/css/plugins/dataTables.bootstrap.css') }}
        {{ HTML::style('/css/alertify.core.css') }}
        {{ HTML::style('/css/alertify.default.css') }}
    @endif
@stop

@section('internalJSLibrary')
    @if (App::environment('production'))
        {{ HTML::script('/js/jquery-1.11.0.js', [], true) }}
        {{ HTML::script('/js/alertify.min.js', [], true) }}
    @else
        {{ HTML::script('/js/jquery-1.11.0.js') }}
        {{ HTML::script('/js/alertify.min.js') }}
    @endif
@stop

@section('internalJSCode')
    <script type="text/javascript">
    jQuery(function($) {
    	$('#addAdmin').click(function() {
    	    window.location.href = '/admin/add';
    	    return false;
    	});

    	$('tr.gradeA td a.userFreeze').on( 'click', function () {
    		var email = $(this).attr('id');

    		alertify.set({ labels: {ok: "承認", cancel: "拒否"} });
    		
			alertify.confirm("このユーザー(" + email + ")をフリーズしますか？", function (e) {
				if (e) {
		    	    @if (App::environment('production'))
		    	    	var setUserAsHide = '{{ secure_url("/ajax/setUserAsFreeze") }}';
		    	    @else
		    	    	var setUserAsHide = '{{ URL::to("/ajax/setUserAsFreeze") }}';
		    	    @endif
		    	    
		    	    $.ajax({
		    	        type: 'POST',
		    	        url: setUserAsHide,
		    	        data: {'email': email, '_token': '{{ csrf_token() }}'},
		    	        dataType: 'json',
		    	        async: false,
		    	        success: function(data) {
		    	            data = JSON.parse(data);

		    	            if (!data.error) {
		    	            	alertify.success(data.message);
		    	            	// reloads after 3 seconds
		    	            	window.setTimeout('location.reload()', 3000);
		    	            } else {
		    	            	alertify.error(data.message);
		    	            }
		    	        }
		    	    });
				}
			});
			
			return false;
		});

    	$('tr.gradeA td a.userActive').on( 'click', function () {
    		var email = $(this).attr('id');
    		
			alertify.confirm("このユーザー(" + email + ")をアクティブしますか？", function (e) {
				if (e) {
		    	    @if (App::environment('production'))
		    	    	var setUserAsHide = '{{ secure_url("/ajax/setUserAsActive") }}';
		    	    @else
		    	    	var setUserAsHide = '{{ URL::to("/ajax/setUserAsActive") }}';
		    	    @endif
		    	    
		    	    $.ajax({
		    	        type: 'POST',
		    	        url: setUserAsHide,
		    	        data: {'email': email, '_token': '{{ csrf_token() }}'},
		    	        dataType: 'json',
		    	        async: false,
		    	        success: function(data) {
		    	            data = JSON.parse(data);

		    	            if (!data.error) {
		    	            	alertify.success(data.message);
		    	            	// reloads after 3 seconds
		    	            	window.setTimeout('location.reload()', 3000);
		    	            } else {
		    	            	alertify.error(data.message);
		    	            }
		    	        }
		    	    });
				}
			});
			
			return false;
		});
    });
    </script>
@stop

@section('content')
    <div id="page-wrapper">
        <br/>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        管理者リスト
                        <button type="submit" class="btn btn-default" id="addAdmin" style="float:right;position:relative;top:-6px">
                            &nbsp;&nbsp;追加&nbsp;&nbsp;
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <div class="dataTables_wrapper form-inline" role="grid">
                                @if (count($adminDataByPage))
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            {{ $paginator->displayPages() }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        {{ $paginator->displayJumpMenu() }}
                                        {{ $paginator->displayItemsPerPage() }}
                                        <span style='margin-left:10px'>Page {{ $paginator->current_page }} of {{ $paginator->num_pages }}</span>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>Operation</th>
                                            <th>Email</th>
                                            <th>Active</th>
                							<th>Date Created</th>
                							<th>Date Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($adminDataByPage as $oneAdmin)
                                            <tr class="gradeA">
                                                <td class="center">
                                                    <a href="/admin/user/update/password/{{ $oneAdmin->userId }}" target="_self">
                                                        <img src="@if (App::environment('production')){{ secure_url('/images/password.png') }}@else{{ URL::to('/images/password.png') }}@endif" title="パスワードを更新" />
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a href="/admin/user/update/acl/{{ $oneAdmin->userId }}" target="_self">
                                                        <img src="@if (App::environment('production')){{ secure_url('/images/acl.png') }}@else{{ URL::to('/images/acl.png') }}@endif" title="ACLを更新" />
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    @if ($oneAdmin->userStatus == 1)
                                                    <a class="userFreeze" href="#" target="_self" id="{{ $oneAdmin->email }}">
                                                        <img src="@if (App::environment('production')){{ secure_url('/images/freeze.png') }}@else{{ URL::to('/images/freeze.png') }}@endif" title="ユーザーをフリーズ" />
                                                    </a>
                                                    @else
                                                    <a class="userActive" href="#" target="_self" id="{{ $oneAdmin->email }}">
                                                        <img src="@if (App::environment('production')){{ secure_url('/images/unfreeze.png') }}@else{{ URL::to('/images/unfreeze.png') }}@endif" title="ユーザーをアクティブ" />
                                                    </a>
                                                    @endif
                                                </td>
                                                <td class="center">{{ $oneAdmin->email }}</td>
                                                <td class="center">@if ($oneAdmin->userStatus == 1) はい @else いいえ @endif</td>
                                                <td class="center">{{ $oneAdmin->dateCreated }}</td>
                                                <td class="center">{{ $oneAdmin->dateUpdated }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            {{ $paginator->displayPages() }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        {{ $paginator->displayJumpMenu() }}
                                        {{ $paginator->displayItemsPerPage() }}
                                        <span style='margin-left:10px'>Page {{ $paginator->current_page }} of {{ $paginator->num_pages }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop