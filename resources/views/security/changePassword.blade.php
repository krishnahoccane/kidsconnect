@include('./layouts/loginReg.header')

<body>

    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">

            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('ui/assets/img/illustrations/auth-reset-password-illustration-light.png') }}"
                        alt="auth-reset-password-cover" class="img-fluid my-5 auth-illustration">
                    {{-- <img src="{{ asset('ui/assets/img/illustrations/auth-forgot-password-illustration-light.png') }}"
                        alt="auth-forgot-password-cover" class="img-fluid my-5 auth-illustration"> --}}

                    <img src="{{ asset('ui/assets/img/illustrations/bg-shape-image-light.png') }}"
                        alt="auth-forgot-password-cover" class="platform-bg">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Forgot Password -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-4 p-sm-5">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset('ui/assets/img/avatars/logo.svg') }}" class="img-fluid w-75"
                                    alt="">
                            </span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1">Reset Password 🔒</h4>
                    @if (Session::has('status'))
                        <div class="alert alert-success">
                            {{ Session::get('status') }}
                        </div>
                        @php
                            Session::forget('status');
                        @endphp
                    @endif
                    <p class="mb-4">for <span class="fw-medium">{{ Auth::user()->email }}</span></p>
                    <form id="formAuthentication" class="mb-3" action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Current Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="current_password" class="form-control"
                                    name="current_password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                <span class="text-danger">
                                    @error('current_password')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">New Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="new_password" class="form-control" name="new_password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                <span class="text-danger">
                                    @error('new_password')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="confirm-password">Confirm Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="confirm-password" class="form-control"
                                    name="confirm_password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                <span class="text-danger">
                                    @error('confirm_password')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100 mb-3">
                            Set new password
                        </button>
                        <div class="text-center">
                            <a href="{{ url('/') }}" class="d-flex align-items-center justify-content-center">
                                <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                                Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
    <!-- / Content -->



    @include('./layouts/loginReg.footer')
