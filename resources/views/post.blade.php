@extends('layouts.frontend.app')

@section('title','Post Details')

@push('css')
    <link href="{{ asset('assets/frontend/css/single-post/styles.css')}}" rel="stylesheet">

	<link href="{{ asset('assets/frontend/css/single-post/responsive.css') }}" rel="stylesheet">
    <style>
		.favourite-post{
			color:red;
		}
        .slider{
            height: 400px;
            width: 100%;
            background-size: cover;
            margin: 0;
            background-image: url({{ Storage::disk('public')->url('posts/'.$post->image) }});
        }
    </style>
@endpush()

@section('content')
<div class="slider">
</div><!-- slider -->

	<section class="post-area section">
		<div class="container">

			<div class="row">

				<div class="col-lg-8 col-md-12 no-right-padding">

					<div class="main-post">

						<div class="blog-post-inner">

							<div class="post-info">

								<div class="left-area">
									<a class="avatar" href="#"><img src="{{ $post->user->role_id == 1 ? Storage::disk('public')->url('profile/admin/'.$post->user->image) :
                                        Storage::disk('public')->url('profile/author/'.$post->user->image)}}" alt="Profile Image"></a>
                                </div>

								<div class="middle-area">
									<a class="name" href="#"><b>{{ $post->user->name }}</b></a>
									<h6 class="date">{{ $post->created_at->diffForHumans() }}</h6>
								</div>

							</div><!-- post-info -->

							<h3 class="title"><a href="#"><b>{{ $post->title }}</b></a></h3>

							<p class="para">{!! $post->body !!}</p>
                            <ul class="tags">
                                @foreach($post->tags as $tag)
								    <li><a href="{{ route('tag.posts',$tag->slug) }}">{{ $tag->name }}</a></li>
                                @endforeach
							</ul>
						</div><!-- blog-post-inner -->

						<div class="post-icons-area">
							<ul class="post-icons">
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

							<ul class="icons">
								<li>SHARE : </li>
								<li><a href="#"><i class="ion-social-facebook"></i></a></li>
								<li><a href="#"><i class="ion-social-twitter"></i></a></li>
								<li><a href="#"><i class="ion-social-pinterest"></i></a></li>
							</ul>
						</div>

					</div><!-- main-post -->
				</div><!-- col-lg-8 col-md-12 -->

				<div class="col-lg-4 col-md-12 no-left-padding">

					<div class="single-post info-area">

						<div class="sidebar-area about-area">
							<h4 class="title"><b>ABOUT {{ $post->user->name }}</b></h4>
							<p>{{ $post->user->about }}</p>
						</div>

						<div class="tag-area">

							<h4 class="title"><b>CATEGORY</b></h4>
							<ul>
                                @foreach($post->categories as $category)
								    <li><a href="{{ route('category.posts',$category->slug )}}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
						</div>

					</div>

				</div><!-- col-lg-4 col-md-12 -->

			</div><!-- row -->

		</div><!-- container -->
	</section><!-- post-area -->


	<section class="recomended-area section">
		<div class="container">
			<div class="row">
                @foreach($randomPost as $post)
                <div class="col-lg-4 col-md-6">
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
			    </div><!-- col-lg-4 col-md-6 -->
                @endforeach
            </div><!-- row -->
		</div><!-- container -->
	</section>

	<section class="comment-section center-text">
		<div class="container">
			<h4><b>POST COMMENT</b></h4>
			<div class="row">

				<div class="col-lg-2 col-md-0"></div>

				<div class="col-lg-8 col-md-12">
					<div class="comment-form">
							<div class="row">
							@guest
								<div class="col-sm-12">
									<p><b><h4>To add your comment, you must be login first</h4></b></p>
								</div>
								<div class="col-sm-12">
									<a class="btn btn-danger" href="{{ route('login') }}" type="submit" id="login"><b>LOGIN</b></a>
								</div>
								
							@else
								<form method="post" action="{{ route('comment.store',$post->id) }}">
									@csrf
									<div class="col-sm-12">
										<textarea name="comment" rows="2" class="text-area-messge form-control"
											placeholder="Enter your comment" aria-required="true" aria-invalid="false"></textarea >
									</div><!-- col-sm-12 -->
									<div class="col-sm-12">
										<button class="submit-btn" type="submit" id="comment"><b>POST COMMENT</b></button>
									</div><!-- col-sm-12 -->
								</form>
							@endguest

							</div><!-- row -->
					</div><!-- comment-form -->
					@if($post->comments->count() > 0)

						<h4><b>COMMENTS <strong>{{ $post->comments->count() }}</strong></b></h4>

						<!-- <div class="commnets-area text-left">

							<div class="comment">

								<div class="post-info">

									<div class="left-area">
										<a class="avatar" href="#"><img src="images/avatar-1-120x120.jpg" alt="Profile Image"></a>
									</div>

									<div class="middle-area">
										<a class="name" href="#"><b>Katy Liu</b></a>
										<h6 class="date">on Sep 29, 2017 at 9:48 am</h6>
									</div>

									<div class="right-area">
										<h5 class="reply-btn" ><a href="#"><b>REPLY</b></a></h5>
									</div>

								</div>

								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
									ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur
									Ut enim ad minim veniam</p>

							</div>

							<div class="comment">
								<h5 class="reply-for">Reply for <a href="#"><b>Katy Lui</b></a></h5>

								<div class="post-info">

									<div class="left-area">
										<a class="avatar" href="#"><img src="images/avatar-1-120x120.jpg" alt="Profile Image"></a>
									</div>

									<div class="middle-area">
										<a class="name" href="#"><b>Katy Liu</b></a>
										<h6 class="date">on Sep 29, 2017 at 9:48 am</h6>
									</div>

									<div class="right-area">
										<h5 class="reply-btn" ><a href="#"><b>REPLY</b></a></h5>
									</div>

								</div>

								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
									ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur
									Ut enim ad minim veniam</p>

							</div>

						</div> --><!-- commnets-area -->
						@foreach($post->comments as $comment)
							<div class="commnets-area text-left">

								<div class="comment">

									<div class="post-info">

										<div class="left-area">
											<a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('/profile/author/'.$comment->user->image) }}" alt="Profile Image"></a>
										</div>

										<div class="middle-area">
											<a class="name" href="#"><b>{{ $comment->user->name }}</b></a>
											<h6 class="date">{{ $comment->created_at->diffForHumans() }}</h6>
										</div>

										<div class="right-area">
											<h5 class="reply-btn" ><a href="#"><b>REPLY</b></a></h5>
										</div>

									</div><!-- post-info -->

									<p>{{ $comment->comment }}</p>

								</div>

							</div><!-- commnets-area -->
						@endforeach

							<a class="more-comment-btn" href="#"><b>VIEW MORE COMMENTS</a>
						@else
							<p class="more-comment-btn"><b>Not Comments Till Now</p>
						@endif
				</div><!-- col-lg-8 col-md-12 -->

			</div><!-- row -->

		</div><!-- container -->
	</section>
@endsection


@push('js')
@endpush()