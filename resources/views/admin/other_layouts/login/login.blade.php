<!DOCTYPE html>
<html lang="en">
<head>
	<title>Treasure Hunt Admin</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/admin/images/settings/favicon.png') }}" type="image/gif" sizes="16x16">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/login.css') }}">
<!--===============================================================================================-->
	
</head>


<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url({{ asset('assets/admin/images/settings/bg-01.jpg') }});">
					<span class="login100-form-title-1">
						Sign In
					</span>
				</div>

				<form method="post" action="{{route('admin.login_submit')}}" class="login100-form validate-form">

					@csrf

					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Username</span>
						<input class="input100" type="text" name="username" placeholder="Enter username" @if(old('username')) value="{{ old('username') }}" @endif>
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" name="password" placeholder="Enter password" @if(old('password')) value="{{ old('password') }}" @endif>
						<span class="focus-input100"></span>
					</div>

					<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<div>
							{{-- <a href="#" class="txt1">
								Forgot Password?
							</a> --}}
							<button class="login100-form-btn">
								Login
							</button>
						</div>
					</div>

					<div class="container-login100-form-btn">
						{{-- <button class="login100-form-btn">
							Login
						</button> --}}
					</div>
				</form>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('assets/admin/js/login.js') }}"></script>
<!--===============================================================================================-->

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

	<script type="text/javascript">
		
		$( document ).ready(function() {

			@if ($errors->any())

				Swal.fire({
				  	title: 'Whoops!',
				  	text: "{{ $errors->first() }}",
				  	type: 'error',
				  	showConfirmButton: false,
				  	timer: 1500
				});

			@endif

		});

	</script>

<!--===============================================================================================-->
</body>
</html>