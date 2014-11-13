@extends('layouts.adminHeader')

@section('content')
    <main class="main">
      <section>
        <div class="content-padd">
          <h3>{{ trans('errors.500') }}</h3>
        </div>
        <br/>
        <a href="/dashboard" class="button--plain">
          <div class="button__inner">
            <div class="button__icon--none">ホームへ戻る</div>
          </div>
        </a>
      </section>
    </main>
@stop
