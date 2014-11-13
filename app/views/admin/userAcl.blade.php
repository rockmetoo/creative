@extends('layouts.adminHeader')

@include('layouts.leftsidemenu')

@section('content')
        <div id="page-wrapper">
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Aclを更新</div>
                        <div class="panel-body">
                            <div class="row">
                                <form name="updateUserAclForm" action="/admin/user/update/acl/{{ $userId }}" method="post" role="form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>ACL Functions</label>
                                            @foreach (Config::get('acl.aclFunctions') as $key=>$text)
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="{{ $key }}" name="acl[]" @if (in_array($key, $userAclSettings)) checked @endif />
                                                    {{ $text }}
                                                </label>
                                            </div>
                                            @endforeach
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