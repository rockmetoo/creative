@extends('layouts.studentHeader')

@include('layouts.leftsidemenu')

@include('layouts.leftSideUserBlock')

@section('internalCSSLibrary')
    @if (App::environment('production'))
        {{ HTML::style('/css/alertify.core.css', [], true) }}
        {{ HTML::style('/css/alertify.default.css', [], true) }}
        {{ HTML::style('/css/jquery.Jcrop.css', [], true) }}
    @else
        {{ HTML::style('/css/alertify.core.css') }}
        {{ HTML::style('/css/alertify.default.css') }}
        {{ HTML::style('/css/jquery.Jcrop.css') }}
    @endif
@stop

@section('internalJSLibrary')
    @if (App::environment('production'))
        {{ HTML::script('/js/jquery-1.11.0.js', [], true) }}
        {{ HTML::script('/js/alertify.min.js', [], true) }}
        {{ HTML::script('/js/jquery.Jcrop.js', [], true) }}
    @else
        {{ HTML::script('/js/jquery-1.11.0.js') }}
        {{ HTML::script('/js/alertify.min.js') }}
        {{ HTML::script('/js/jquery.Jcrop.js') }}
    @endif
@stop

@section('internalJSCode')
    <script type="text/javascript">
    jQuery(function($) {
        var jcropAPI = null;
        
        $('#profilePictureUpload').change(function() {
            var file = this.files[0];
            var name = file.name;
            var size = file.size;
            var type = file.type;
        
            var formData = new FormData();
            formData.append('profilePictureFile', $('#profilePictureUpload').prop('files')[0]);
        
            $.ajax({
                type: 'POST',
                url: '/profile/picture/upload',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload) {
                        myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                    }
                    return myXhr;
                },
                data: formData,
                dataType : 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                	$('#profilePicProgress').show();
                    if (jcropAPI) jcropAPI.destroy();
                },
                success: function(data) {
                	data = JSON.parse(data);
                    
    	            if (!data.error) {
        	            // TODO: alter image src and activate crop function
    	            	$('#profilePicture').attr("src", "@if (App::environment('production')){{ secure_url('/pf/tp1/') }}@else{{ URL::to('/pf/tp1/') }}@endif/" + Math.floor((Math.random() * 1000) + 1));
    	            	$('#profilePicProgress').hide();

    	            	$('#profilePicture').Jcrop(
    	            		{
    	            		    aspectRatio: 160 / 160,
    	            		    setSelect: [ 30, 30, 100, 100 ],
    	            		    onSelect: updateCoords
    	            		}, function() {
    	                    jcropAPI = this;
    	                });
    	            } else {
    	            	$('#profilePicProgress').hide();
    	            	alertify.alert(data.message);
    	            }
                },
                error: function() {
                	$('#profilePicProgress').hide();
                	alertify.alert('Error: something happened wrong. Please try again.');
                }
            });
        });

        function progressHandlingFunction(e) {
            if(e.lengthComputable){
                $('#profilePicProgress').attr({value: e.loaded, max: e.total});
            }
        }

        function updateCoords(c) {
        	$('#x').val(c.x);
        	$('#y').val(c.y);
        	$('#w').val(c.w);
        	$('#h').val(c.h);
        }
    });
    </script>
@stop

@section('content')
    <div id="page-wrapper">
        <br/>
        <div class="row rowContainer">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>
                    <div class="panel-body">
                        @if (null !== Session::get('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="row">
                            <form name="profileForm" action="/profile" method="post" role="form" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" id="x" name="x" value="30" />
                                <input type="hidden" id="y" name="y" value="30" />
                                <input type="hidden" id="w" name="w" value="100" />
                                <input type="hidden" id="h" name="h" value="100" />
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="firstName" value="{{ (Input::old('firstName')) ? Input::old('firstName') : $profile[0]->firstName }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="lastName" value="{{ (Input::old('lastName')) ? Input::old('lastName') : $profile[0]->lastName }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Post Code</label>
                                        <input type="text" class="form-control" name="postCode" value="{{ (Input::old('postCode')) ? Input::old('postCode') : $profile[0]->postCode }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control" name="address" value="{{ (Input::old('address')) ? Input::old('address') : $profile[0]->address }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="thumbnail pull-left">
                                            @if ($USER_PROFILE['p2'])
                                                <img id="profilePicture" width="160" height="160" src="@if (App::environment('production')){{ secure_url('/pf/p2/') }}@else{{ URL::to('/pf/p2/') }}@endif" alt="{{ $USER_PROFILE['fullName'] }}"></img>
                                            @else
                                                <img id="profilePicture" width="160" height="160" src="@if (App::environment('production')){{ secure_url('/images/anonymous.png') }}@else{{ URL::to('/images/anonymous.png') }}@endif" alt="{{ $USER_PROFILE['fullName'] }}"></img>
                                            @endif
                                            <br/>
                                            <progress id="profilePicProgress" style="display:none"></progress>
                                        </div>
                                        <div class="pull-right">
                                            <input type="file" name="profilePictureUpload" id="profilePictureUpload" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-default">Update</button>
                                </div>
                            </form>
                        </div>
                        <h1 class="page-header"></h1>
                        <div class="row">
                            <div class="col-lg-6">
                                <a href="/change/password" class="btn btn-primary btn-lg" role="button">Change Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop