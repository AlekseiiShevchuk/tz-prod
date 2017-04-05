<div class="abonne-title">
    {!!  trans('membre.title') !!}
</div>
<div class="content-white padding-to-footer">

    @if(Session::has('flash_message'))
        <div class="alert membre-block-form {{ Session::has('flash_message_type') ? Session::get('flash_message_type') : '' }}">
            {{ Session::get('flash_message') }}
        </div>
    @endif

    <div name="membre" class="membre">
        <div class="membre-block-form">
            <p class="text-beginning" style="text-align: center!important;">{!!  trans('membre.text-beginning') !!}</p>

            <form method="post" class="membre-form" enctype="multipart/form-data">

                {{ csrf_field() }}
                <p>{!!  trans('membre.name') !!}</p>
                <input type="text" name="name" placeholder="{!!  trans('membre.name') !!}" value="{{$item->name}}"/>
                <p>{!!  trans('membre.surname') !!}</p>
                <input type="text" name="surname" placeholder="{!!  trans('membre.surname') !!}" value="{{$item->surname}}"/>
                <p>{!!  trans('membre.nickname') !!}</p>
                <input type="text" name="nickname" placeholder="{!!  trans('membre.nickname') !!}" value="{{$item->nickname}}"/>
                @if ( isset($errors) && $errors->has('nickname'))
                    <div class="help-block">
                        <strong>{{ $errors->first('nickname') }}</strong>
                    </div>
                @endif
                <p>{!!  trans('app.email') !!}</p>
                <input type="text" name="email" disabled  value="{{$item->email}}"/>
                @if ( isset($errors) && $errors->has('password'))
                    <div class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                @endif
                <p>{!!  trans('membre.password') !!}</p>
                <input name="password" maxlength="30" class="membre-profile_password margin-right" type="password" placeholder="{!!  trans('app.password') !!}"/>
                <input name="confirm-password" maxlength="30" class="membre-profile_password" type="password" placeholder="{!!  trans('membre.rep-new-pas') !!}"/>
                <p>{!!  trans('membre.data-bth') !!}</p>

                <select name="date" id="date" value="{{$item->getBirthDayDate()->day}}"></select>

                <select name="month" months="{!! trans('app.months') !!}" id="month" value="{{$item->getBirthDayDate()->month}}"></select>

                <select name="year" id="year" value="{{$item->getBirthDayDate()->year}}"></select>

                <div class="gender">
                    <p>{!!  trans('membre.gender') !!}</p>
                    <input id="man" type="radio" name="gender" value="man" {{ $item->gender == 'man' ? 'checked' : '' }}>
                    <label for="man">{!!  trans('membre.man') !!}</label>
                    <input id="woman" type="radio" name="gender" value="woman" {{ $item->gender == 'woman' ? 'checked' : '' }}>
                    <label for="woman">{!!  trans('membre.women') !!}</label>
                </div>

                {{--<select class="country">--}}
                    {{--<option  selected="selected">Country</option>--}}
                    {{--<option >USA</option>--}}
                    {{--<option >England</option>--}}
                {{--</select>--}}

                <p>{!!  trans('membre.country') !!}</p>
                <div class="req_field">
                {!! View::make('widgets.InputSelectCountries', ['country_id' => $item->country_id, 'class' => 'country']) !!}
                </div>
                <p>{!!  trans('membre.city') !!}</p>
                <input type="text" name="city" maxlength="255" placeholder="{!!  trans('membre.city') !!}" value="{{$item->city}}">
                <p>{!!  trans('membre.z-code') !!}</p>
                <input type="text" name="zip" maxlength="10" placeholder="{!!  trans('membre.z-code') !!}" value="{{$item->zip}}">
                <p>{!!  trans('membre.address') !!}</p>
                <input type="text" name="address1" maxlength="255" placeholder="{!!  trans('membre.address') !!}" value="{{$item->address1}}">
                <p>{!!  trans('membre.address2') !!}</p>
                <input type="text" name="address2" maxlength="255" placeholder="{!!  trans('membre.address2') !!}" value="{{$item->address2}}">
                <div class="phone-numder">
                    <p>{!!  trans('membre.phone') !!}</p>
                    <p>{!!  trans('membre.phone2') !!}</p>
                    <input name="phone_country_code"  placeholder="{!!  trans('membre.phone') !!}"  maxlength="6" onlynumbers="true" type="text" value="{{$item->phone_country_code ?: ''}}">
                    <input name="phone"  placeholder="{!!  trans('membre.phone2') !!}"  onlynumbers="true" maxlength="20" type="text" value="{{$item->phone}}">
                    @if ( isset($errors) && $errors->has('phone'))
                        <div class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </div>
                    @endif
                </div>
                <p>{!!  trans('membre.photo') !!}</p>
                <div class="photo">
                    <div class="photo-img {{!$item->getImage() ?: 'isset'}}">
                        <img id="avatar" src="{{$item->getImage() ?: 'src/img/photo.png'}}">
                    </div>
                    <div class="file_upload">
                        <input id="loader" type="file" name="image" value="{{$item->getImage() ?: ''}}" />
                        <button type="button">{!!  trans('membre.upload') !!}</button>
                    </div>
                </div>

                <div class="subscribe_news">
                    <label class="check-box">
                        <input type="checkbox" name="subscribe_news" value="1" {{ $item->subscribe_news === 1 ? 'checked' : '' }}> {!!  trans('membre.subscribe_news') !!}
                    </label>
                </div>

                <input type="submit" class="button membre-profile_buttom" value="{!!  trans('membre.save') !!}"/>
                <a href="#" go-prev class="membre-profile_buttom">{!!  trans('membre.exit') !!}</a>
                <p>{!!  trans('membre.text-under-button') !!}</p>
            </form>
        </div>
    </div>
</div>
