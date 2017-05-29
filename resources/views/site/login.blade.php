<div class="abonne-title">
    {!!  trans('login.title') !!}
</div>
<div class="content-white">
    <div name="membre" class="membre">
        <div class="membre-block-form">
            <div class="text-abone-top">
                @if(empty(Cookie::get('is_old_visitor')))
                    {!!  trans('login.text-top_first_time_visitor') !!}
                @else
                {!!  trans('login.text-top_old_visitor') !!}
                @endif
            </div>
            <p></p>
            <form action="" method="post" role="form" class="membre-form crop" action="{{ url('/login') }}">
                {{ csrf_field() }}
<div class="row">

</div>
                @if ($errors->has('login'))
                    <div class="help-block">
                        <strong>{{ $errors->first('login') }}</strong>
                    </div>
                @endif

                <input type="text"
                       id="e-mail"
                       placeholder="{!!  trans('login.email') !!}"
                       name="login"
                       required
                       oninvalid="this.setCustomValidity('Remplissez le champ')"
                       oninput="setCustomValidity('')"
                       value="<?php echo request()->old('login'); ?>"/>

                <label for="e-mail">*</label>

                @if ($errors->has('password'))
                    <div class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                @endif

                <input type="password" id="password" placeholder="{!!  trans('app.password') !!}" name="password"
                       required
                       oninvalid="this.setCustomValidity('Remplissez le champ')"
                       oninput="setCustomValidity('')"/>
                <label for="password">*</label>

                @if ($errors->has('name'))
                    <div class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </div>
                @endif

                <input type="text"
                       id="name"
                       placeholder="Prénom"
                       name="name"
                       oninvalid="this.setCustomValidity('Remplissez le champ')"
                       required
                       {{--oninvalid="this.setCustomValidity('Remplissez le champ')"--}}
                       {{--oninput="setCustomValidity('')"--}}
                       value="<?php echo Cookie::get('name') ?? request()->old('name'); ?>"/>

                <label for="name">*</label>

                @if ($errors->has('country_id'))
                    <div class="help-block">
                        <strong>{{ $errors->first('country_id') }}</strong>
                    </div>
                @endif
                {!! View::make('widgets.InputSelectCountries', ['country_id' => Cookie::get('country_id') ?? App\Services\GeoIPService::getCountryIdForCurrentVisitor(), 'class' => 'country']) !!}
                <label for="country_id">*</label>


                <div class="remember">
                    <label class="check-box">
                        <input type="checkbox" name="remember"> {!!  trans('login.remember') !!}
                    </label>
                </div>

                <input type="submit" class="button" value="{!! trans('login.login_reg') !!}"/>

                <p class="membre-text" style="text-align: center!important; margin-top: -30px;">
                    <a href="{{ url('/password/reset') }}">{!! trans('login.forgot_password') !!}</a>
                </p>

                <br/>
            </form>
            <p class="membre-line small"></p>
            <p class="membre-ou">{!!  trans('login.if') !!}</p>
            <div class="membre-socials-block">
                @if ($fb_login_url)
                    <a href="{{$fb_login_url}}" class="button-membre">{!!  trans('login.title-fb') !!}</a>
                @endif
                <a href="{{ url('/google/callback') }}" class="button-membre">{!!  trans('login.title-google') !!}</a>
            </div>
            <p class="req">*{!!  trans('login.req') !!}</p>
        </div>
        <p class="membre-line"></p>
        <p class="membre-text">
            {!!  trans('login.text') !!}</p>
        </p>
    </div>
</div>