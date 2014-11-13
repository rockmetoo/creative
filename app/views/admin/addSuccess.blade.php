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
                        <div class="alert alert-success">
                            新しい管理者は、データベースに正常に追加されました。
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop