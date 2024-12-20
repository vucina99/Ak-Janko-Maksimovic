@extends("content")

@section('content')
    <div class="setHeight d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-12 ">
                    <div class="card border-blue">
                        <div class="card-header  pt-0 pb-0 bg-personal-light w-100 d-flex justify-content-center">
                            <div class="w-25 text-center pb-2 pt-2">
                                <img src="{{asset('img/logo-images/empty-logo.png')}}" class="w-25" alt="small-logo">
                            </div>
                        </div>

                        <div class="card-body "><br>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="email"
                                           class="col-md-4 col-form-label text-md-end">{{ __('E-MAIL') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="text"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}" autocomplete="email" autofocus

                                        >

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password"
                                           class="col-md-4 col-form-label text-md-end">{{ __('LOZINKA') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password" autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input down-margin" type="checkbox" name="remember"
                                                   id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label small-text" for="remember">
                                                {{ __('ZAPAMTI ME') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('PRIJAVA') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link withoutStricke"
                                               href="{{ route('password.request') }}">
                                                {{ __('OBNOVI LOZINKU?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
