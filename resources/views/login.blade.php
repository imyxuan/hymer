@extends('hymer::auth.master')

@section('content')
    <div class="login-container">

        <p>{{ __('hymer::login.signin_below') }}</p>

        <form action="{{ route('hymer.login') }}" method="POST">
            {{ csrf_field() }}
            <div class="mb-3 form-group" id="emailGroup">
                <label class="form-label">{{ __('hymer::generic.email') }}</label>
                <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('hymer::generic.email') }}" class="form-control" required>
            </div>

            <div class="mb-3 form-group" id="passwordGroup">
                <label class="form-label">{{ __('hymer::generic.password') }}</label>
                <input type="password" name="password" placeholder="{{ __('hymer::generic.password') }}" class="form-control" required>
            </div>

            <div class="mb-3" id="rememberMeGroup">
                <input type="checkbox" name="remember" id="remember" value="1" class="form-check-input">
                <label for="remember" class="form-check-label remember-me-text">{{ __('hymer::generic.remember_me') }}</label>
            </div>

            <button type="submit" class="btn btn-primary login-button">
                <span class="signing-in d-none">
                    <span class="hymer-refresh"></span>
                    {{ __('hymer::login.loggingin') }}...
                </span>
                <span class="sign-in">{{ __('hymer::generic.login') }}</span>
            </button>

        </form>

        <div style="clear:both"></div>

        @if(!$errors->isEmpty())
            <div class="alert alert-red">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div> <!-- .login-container -->
@endsection

@section('script')

    <script>
        let btn = document.querySelector('button[type="submit"]')
        let form = document.forms[0]
        let email = document.querySelector('[name="email"]')
        let password = document.querySelector('[name="password"]')
        btn.addEventListener('click', function(ev){
            if (form.checkValidity()) {
                btn.querySelector('.signing-in').className = 'signing-in'
                btn.querySelector('.sign-in').className = 'sign-in d-none'
            } else {
                ev.preventDefault()
            }
        })
        email.focus()
        document.getElementById('emailGroup').classList.add("focused")

        // Focus events for email and password fields
        email.addEventListener('focusin', function(){
            document.getElementById('emailGroup').classList.add("focused")
        })
        email.addEventListener('focusout', function(){
            document.getElementById('emailGroup').classList.remove("focused")
        })

        password.addEventListener('focusin', function(){
            document.getElementById('passwordGroup').classList.add("focused")
        })
        password.addEventListener('focusout', function(){
            document.getElementById('passwordGroup').classList.remove("focused")
        })
    </script>
@endsection
