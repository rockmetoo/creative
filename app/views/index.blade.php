@extends('layouts.indexHeader')

@section('content')
    <div id="landing-content">
        <div class="indexImageItem">
            <img src="/images/indexImage1.jpg" alt="" class="img-background"></img>
        </div>
        <div class="bg-white">
            <div id="contact" class="text-center content-padding">
                <div class="container">
                    <h3>NEWSLETTER SIGNUP</h3>
                    <p class="m-bottom-md">Subscribing to our newsletter you will always be update with the latest news from us.</p>
                    <form class="form-inline content-padding">
                        <div class="form-group">
                            <label class="sr-only">Email address</label>
                            <input id="newsletter" class="form-control input-lg" type="text" placeholder="Email Address">
                        </div>
                        <a class="btn btn-lg btn-info" href="#">Subscribe</a>
                    </form>
                </div>
            </div>
            <br/>
        </div>
    </div>
@stop

@section('footer')
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-3 padding-md">
                    <p class="font-lg">About Our Company</p>
                    <p>
                        <small>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum auctor suscipit lobortis.
                        </small>
                    </p>
                </div>
                <div class="col-sm-3 padding-md">
                    <p class="font-lg">Useful Links</p>
                    <ul class="list-unstyled useful-link">
                        <li><a href="/portfolio"><small>Our Portfolio</small></a></li>
                        <li><a href="/portfolio"><small>Our Portfolio</small></a></li>
                        <li><a href="/portfolio"><small>Our Portfolio</small></a></li>
                    </ul>
                </div>
                <div class="col-sm-3 padding-md">
                    <p class="font-lg">Stay Connect</p>
                    <a href="#" class="social-connect tooltip-test facebook-hover pull-left m-right-xs" data-toggle="tooltip" data-original-title="Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="#" class="social-connect tooltip-test twitter-hover pull-left m-right-xs" data-toggle="tooltip" data-original-title="Twitter">
                        <i class="fa fa-twitter"></i>
                    </a>
                </div>
                <div class="col-sm-3 padding-md">
                    <p class="font-lg">Contact Us</p>
                    Email : contact@schooler.com
                    <div class="seperator"></div>
                    <a class="btn btn-info">
                        <i class="fa fa-envelope"></i>
                        Contact support
                    </a>
                </div>
            </div>
        </div>
    </footer>
@stop