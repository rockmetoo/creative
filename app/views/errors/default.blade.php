@extends('layouts.basicHeader')

@section('content')
	<main class="main">
		<section>
			<div class="content-padd">
				<h3>エラーが発生しました。ホームボタンで戻ってください。</h3>
			</div>
			<br/>
			<a href="/" class="button--plain">
				<div class="button__inner">
					<div class="button__icon--none">ホームへ戻る</div>
				</div>
			</a>
		</section>
	</main>
@stop
