@extends('site.layouts.main')

@section('content')
    <div class="abonne-title">
        {!!  trans('reset.title') !!}
    </div>

    <div class="content-white">
        <div class="membre">
            <div class="membre-block-form">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ url('/password/reset') }}" method="post" role="form" class="membre-form crop">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif

                    <p style="width: 283px;margin-left: 22%;">{!! trans('reset.enter_email') !!}</p>

                    <input type="text"
                           id="e-mail"
                           placeholder="{!!  trans('reset.email') !!}"
                           name="email"
                           value="{{ $email or old('email') }}"
                           required
                           disabled
                           autofocus
                           oninvalid="this.setCustomValidity('Remplissez le champ')"
                           oninput="setCustomValidity('')"/>

                    <label for="e-mail">*</label>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif

                    <p style="width: 283px;margin-left: 22%;">{!! trans('reset.password') !!}</p>

                    <input id="password"
                           type="password"
                           placeholder="{!! trans('reset.password') !!}"
                           class="form-control"
                           name="password"
                           required/>

                    <label for="password">*</label>

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif

                    <p style="width: 283px;margin-left: 22%;">{!! trans('reset.confirm_password') !!}</p>

                    <input id="password-confirm"
                           type="password"
                           class="form-control"
                           placeholder="{!! trans('reset.confirm_password') !!}"
                           name="password_confirmation"
                           required/>

                    <label for="password-confirm">*</label>

                    <input type="submit" class="button" value="{!! trans('reset.reset_password') !!}"/>

                    <p class="req" style="color: #4e69b0;width: 300px;margin: 0 auto;padding-bottom: 20px;">*{!! trans('login.req') !!}</p>
                </form>
            </div>
            <p class="membre-line"></p>
            <p class="membre-text">
                {!!  trans('login.text') !!}
            </p>
        </div>
    </div>
@endsection
