<div class="abonne-title">
    {!!  trans('contacts.title') !!}
</div>
<div class="content-white">
    <div name="membre" class="membre">


        <div class="membre-block-form">

            <div class="tzinfo-block-text_text text-align-center">
                <div>{!!  trans('contacts.text') !!}</div>
            </div>

            @if(Session::has('flash_message'))
                <div class="alert membre-block-form {{ Session::has('flash_message_type') ? Session::get('flash_message_type') : '' }}">
                    {{ Session::get('flash_message') }}
                </div>
            @endif

            <form method="post" role="form" class="membre-form crop" action="{{ url('/contacts/send') }}">


                {{ csrf_field() }}

                <input type="text" name="name" placeholder="{!!  trans('membre.name') !!}"
                       value="{{ request()->old('name', isset($item) ? $item->name : '') }}"/>
                <input type="text" name="surname" placeholder="{!!  trans('membre.surname') !!}"
                       value="{{ request()->old('surname', isset($item) ? $item->surname : '') }}"/>

                @if ($errors->has('email'))
                    <div class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>
                @endif

                <input type="text" id="e-mail" placeholder="{!!  trans('app.email') !!}" name="email" required
                       oninvalid="this.setCustomValidity('Remplissez le champ')"
                       oninput="setCustomValidity('')"
                       value="{{ request()->old('email', isset($item) ? $item->email : '')}}"/>
                <label for="e-mail">*</label>

                {!! View::make('widgets.InputSelectCountries', ['country_id' => request()->old('country_id', isset($item) ? $item->country_id : 0), 'class' => 'country']) !!}

                @if ($errors->has('message'))
                    <div class="help-block">
                        <strong>{{ $errors->first('message') }}</strong>
                    </div>
                @endif

                <div class="textarea-box">
                <textarea placeholder="{!!  trans('contacts.message') !!}" name="message" id="message"
                          required>{{ request()->old('message') }}</textarea>
                    <label for="message">*</label>
                </div>



                @if(!Auth::check())

                    @if ($errors->has('g-recaptcha-response'))
                        <div class="help-block">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </div>
                    @endif

                    {!! Recaptcha::render([ 'lang' => App::getLocale() ]) !!}

                @endif

                <input type="submit" class="button" value="{!! trans('contacts.send') !!}"/>
            </form>
            <p class="req">*{!!  trans('contacts.req') !!}</p>
            <div class="grey-text text-align-center font-size-13">{!!  trans('contacts.text-2') !!}</div>
        </div>
    </div>
</div>