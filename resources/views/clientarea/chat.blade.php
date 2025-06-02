@extends('layouts.mobile')

@section('content')
	<style>
		html, body {
			height: 100%;
		}
		.main-container {
			height: calc(100% - 115px);
		}
	</style>
	<div class="row" style="height: 100%">
		<div class="col-12 p-0">
			<div class="card d-block m-0" style="height: 100%">
				<div class="card-body">
					<div class="chat-content m-0 ps ps--active-y" style="padding:15px; padding-bottom:30px;height: 85%;">
						@foreach ($chat as $message)
							@if ($message->user_id)
								<div class="chat-content-leftside">
									<div class="d-flex">
										<div class="flex-grow-1 ms-2">
											<p class="mb-0 chat-time">{{__('web.support')}}, {{ date('d/m/Y H:i', strtotime($message->created_at)) }}</p>
											<p class="chat-left-msg">{{$message->message}}</p>
										</div>
									</div>
								</div>
							@else
								<div class="chat-content-rightside">
									<div class="d-flex ms-auto">
										<div class="flex-grow-1 me-2">
											<p class="mb-0 chat-time text-end">{{__('web.you')}}, {{ date('d/m/Y H:i', strtotime($message->created_at)) }}</p>
											<p class="chat-right-msg">{{$message->message}}</p>
										</div>
									</div>
								</div>
							@endif
						@endforeach
						<div class="ps__rail-x" style="left: 0px; bottom: 0px;">
							<div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;">
							</div>
						</div>
						<div class="ps__rail-y" style="top: 0px; height: 520px; right: 0px;">
							<div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 235px;">
							</div>
						</div>
					</div>
					<div class="chat-footer d-flex w-100" style="left:unset">
						<div class="flex-grow-1 pe-2">
							<div class="input-group text-start"> 
							<span class="input-group-text"><i class='bx bx-message'></i></span>
							<form action="{{ route('chat.store') }}" class="hidden" id="newmassage" name="newmassage" method="POST">
								@csrf
							</form>
							<textarea id="text" name="message" rows="1" class="form-control" form="newmassage" style="resize: none;"></textarea>
							<span class="input-group-text text-primary">
								<button type="submit" form="newmassage" class="input-group-text text-primary" style="border:0;outline:none">
								<i class='bx bxs-send'></i>
								</button>
							</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			let $chatContent = $('.chat-content');
			$chatContent.scrollTop($chatContent.prop("scrollHeight"));
		});
	
	</script>
@endsection