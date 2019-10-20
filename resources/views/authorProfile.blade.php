@extends('layouts.frontend.app')

@section('title')
{{ $targetAuthor->name }} Profile
@endsection

@push('css')
<link href="{{ asset('assets/frontend/css/author-post/styles.css') }}" rel="stylesheet">
<link href="{{ asset('assets/frontend/css/author-post/responsive.css') }}" rel="stylesheet">
<style>
		.favourite-post{
			color:red;
		}
        .slider{
            height: 300px;
            width: 100%;
            background-size: cover;
            margin: 0;
            background-image: url("");/* Category image */
        }
</style>
@endpush

@section('content')


<div class="slider display-table center-text">
		<h1 class="title display-table-cell"><b>{{ $targetAuthor->name }}</b></h1>
</div><!-- slider -->

<section class="blog-area section">
		<div class="container">

			<div class="row">

				<div class="col-lg-8 col-md-12">
					<div class="row">
					@if($posts->count() > 0)
						@foreach($posts as $post)
							<div class="col-md-6 col-sm-12">
								<div class="card h-100">
									<div class="single-post post-style-1">

										<div class="blog-image"><img src="{{ Storage::disk('public')->url('posts/'.$post->image)}}" alt="{{ $post->image }}"></div>

										<a class="avatar" href="{{ route('author.profile', $post->user->username ) }}"><img src="{{ $post->user->user_id == 1 ? Storage::disk('public')->url('profile/admin/'.$post->user->image) : 
															Storage::disk('public')->url('profile/author/'.$post->user->image) }}" 
															width="48" height="48" alt="poster"></a>

										<div class="blog-info">

											<h4 class="title"><a href="{{ route('post.index',$post->slug) }}"><b>{{ $post->title }}</b></a></h4>

											<ul class="post-footer">
											<li>
											@guest
											
												<a href="javascript::void(0);" onclick="toastr.info('To add favourite list, You need to login firat','Info',{
													closeButton: true,
													progressBar : true
												})"><i class="ion-heart"></i>{{ $post->favourite_to_users->count() }}</a>
											
											@else

												<a href="javascript::void(0);" onclick="document.getElementById('favourite-form-{{ $post->id }}').submit();"
												class="{{ Auth::User()->favourite_posts->where('pivot.post_id',$post->id)
												->count() == 0 ? '':'favourite-post' }}">
												<i class="ion-heart"></i>{{ $post->favourite_to_users->count() }}</a>
											
												<form id="favourite-form-{{ $post->id }}" action="{{ route('favourite.post', $post->id)}}" method="POST" style="display:none;">
											@csrf
											</form>
												
											@endguest
											</li>
												
												<li><a href="#"><i class="ion-chatbubble"></i>{{ $post->comments->count() }}</a></li>
												<li><a href="#"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
											</ul>

										</div><!-- blog-info -->
									</div><!-- single-post -->
								</div><!-- card -->
							</div><!-- col-md-6 col-sm-12 -->
						@endforeach
					@else
						<div class="col-lg-8 col-md-12">
							<div class="card h-100">
								<div class="single-post post-style-1">
									<div class="blog-info">

										<h3 class="title">
												<strong>Sorry, No Post Found:  </strong>
										</h3>
										
									</div><!-- blog-info -->
								</div><!-- single-post -->
							</div><!-- card -->
						</div><!-- col-lg-8 col-md-12 -->
					@endif
					</div><!-- row -->

				</div><!-- col-lg-8 col-md-12 -->

				<div class="col-lg-4 col-md-12 ">

					<div class="single-post info-area ">

						<div class="about-area">
							<h4 class="title"><b>ABOUT Author </b></h4>
							<p>{{ $targetAuthor->name }}</p><br>
							<p>{{ $targetAuthor->about }}</p><br>
							<strong>Author Since: {{ $targetAuthor->created_at->toDateString() }}</strong>
							<strong>Author Total Posts: <b>{{ $targetAuthor->posts->count() }}</b></strong>
						</div>

					</div><!-- info-area -->

				</div><!-- col-lg-4 col-md-12 -->

			</div><!-- row -->

		</div><!-- container -->
	</section><!-- section -->
@endsection

@push('js')

@endpush