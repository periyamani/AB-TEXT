{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<!doctype html>
<html class="no-js" lang="">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Diagnostics</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Favicon -->
	{{-- <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png"> --}}
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('auth/css/bootstrap.min.css')}}">
	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ asset('auth/css/fontawesome-all.min.css')}}">
	<!-- Flaticon CSS -->
	<link rel="stylesheet" href="{{ asset('auth/font/flaticon.css')}}">
	<!-- Google Web Fonts -->
	{{-- <link href="../fonts.googleapis.com/css89ea.css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet"> --}}
	<!-- Custom CSS -->
	<link rel="stylesheet" href="{{ asset('auth/css/style.css')}}">
</head>

<body>
	<!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
	<section class="fxt-template-animation fxt-template-layout21">
		<!-- Animation Start Here -->
		<div id="particles-js"></div>
		<!-- Animation End Here -->
		<div class="container">
			<div class="row align-items-center justify-content-center">
				<div class="col-xl-6 col-lg-7 col-sm-12 col-12 fxt-bg-color">
					<div class="fxt-content">
						<div class="fxt-header">
							<h2><img src="{{asset('images/ISMT_Logo.png')}}" alt="" srcset=""></h2>
							<p>Reset Password</p>
						</div>
						<div class="fxt-form">
							<form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
								<div class="form-group">
									<div class="fxt-transformY-50 fxt-transition-delay-1">
                                        <input id="email" type="email" readonly class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror                                     
									</div>
								</div>
								<div class="form-group">
									<div class="fxt-transformY-50 fxt-transition-delay-2">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
									</div>
								</div>
                                <div class="form-group">
									<div class="fxt-transformY-50 fxt-transition-delay-2">
                                        
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                                        
									</div>
								</div>
								<div class="form-group">
									<div class="fxt-transformY-50 fxt-transition-delay-4">
										<button type="submit" class="fxt-btn-fill">Reset Password</button>
									</div>
								</div>
							</form>
						</div>
						{{-- <div class="fxt-style-line">
							<div class="fxt-transformY-50 fxt-transition-delay-5">
								<h3>Or Login With</h3>
							</div>
						</div> --}}
						{{-- <ul class="fxt-socials">
							<li class="fxt-google">
								<div class="fxt-transformY-50 fxt-transition-delay-6">
									<a href="#" title="google"><i class="fab fa-google-plus-g"></i><span>Google +</span></a>
								</div>
							</li>
							<li class="fxt-twitter">
								<div class="fxt-transformY-50 fxt-transition-delay-7">
									<a href="#" title="twitter"><i class="fab fa-twitter"></i><span>Twitter</span></a>
								</div>
							</li>
							<li class="fxt-facebook">
								<div class="fxt-transformY-50 fxt-transition-delay-8">
									<a href="#" title="Facebook"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
								</div>
							</li>
						</ul> --}}
						<div class="fxt-footer">
							<div class="fxt-transformY-50 fxt-transition-delay-9">
								<p>Already have an account?<a href="/login" class="switcher-text2 inline-text">Login</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- jquery-->
	<script src="{{ asset('auth/js/jquery-3.5.0.min.js')}}"></script>
	<!-- Popper js -->
	<script src="{{ asset('auth/js/popper.min.js')}}"></script>
	<!-- Bootstrap js -->
	<script src="{{ asset('auth/js/bootstrap.min.js')}}"></script>
	<!-- Imagesloaded js -->
	<script src="{{ asset('auth/js/imagesloaded.pkgd.min.js')}}"></script>
	<!-- Particles js -->
	<script src="{{ asset('auth/js/particles.min.js')}}"></script>
	<script src="{{ asset('auth/js/particles-1.js')}}"></script>
	<!-- Validator js -->
	<script src="{{ asset('auth/js/validator.min.js')}}"></script>
	<!-- Custom Js -->
	<script src="{{ asset('auth/js/main.js')}}"></script>

</body>
</html>