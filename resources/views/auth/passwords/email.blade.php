@extends('site.layouts.main')

        <!-- Main Content -->
@section('content')
    <div class="abonne-title">
        {!!  trans('reset.title') !!}
    </div>

    <div class="content-white">
        <div class="membre">
            <div class="membre-block-form">
                @if (session('status'))
                    <div class="alert alert-success" style="font-size: 18px; color:#4e69b0" >
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ url('/password/email') }}" method="post" role="form" class="membre-form crop">
                    {{ csrf_field() }}
                    @if ($errors->has('email'))
                        <div class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif

                    <p style="width: 283px;margin-left: 22%;">{!! trans('reset.enter_email') !!}</p>

                    <input type="text"
                           id="e-mail"
                           placeholder="{!!  trans('reset.email') !!}"
                           name="email"
                           required
                           oninvalid="this.setCustomValidity('Remplissez le champ')"
                           oninput="setCustomValidity('')"/>

                    <label for="e-mail">*</label>

                    <input type="submit" class="button" value="{!! trans('reset.send_password_reset_link') !!}"/>

                    <p class="req" style="color: #4e69b0;width: 300px;margin: 0 auto;">*{!! trans('login.req') !!}</p>

                    <p class="membre-text" style="text-align: center!important;">
                        <a href="{{ url('/login') }}">{!! trans('login.login_reg') !!}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection
