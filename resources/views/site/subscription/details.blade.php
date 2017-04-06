<div class="abonne-title">
    {!!  trans('abonne.title') !!}
</div>
<div class="content-white">

    @if(Session::has('flash_message'))
        <div class="alert membre-block-form">
            {{ Session::get('flash_message') }}
        </div>
    @endif

    <div name="membre" class="membre buy">
        <div class="membre-block-form">
            <form method="post" role="form" class="membre-form crop" action="{{ url('abonne/save/' . $id) }}">
                {{ csrf_field() }}

                <input type="hidden" name="invite" value="{{ $invite }}">

                @if ($errors->has('holder'))
                    <div class="help-block">
                        <strong>{{ $errors->first('holder') }}</strong>
                    </div>
                @endif
                <input type="text" id="holder" maxlength="32" placeholder="{!!  trans('details.card-holder') !!}" name="holder" required/>
                <label for="holder">*</label>

                @if ($errors->has('pan'))
                    <div class="help-block">
                        <strong>{{ $errors->first('pan') }}</strong>
                    </div>
                @endif
                @if ($errors->has('cvc'))
                    <div class="help-block">
                        <strong>{{ $errors->first('cvc') }}</strong>
                    </div>
                @endif
                <input type="text" onkeyup="this.value = this.value.replace (/\D/, '')" pattern="[0-9]{13,19}" maxlength="19" id="pan" placeholder="{!!  trans('details.card-number') !!}" name="pan" required/>
                <input type="text" onkeyup="this.value = this.value.replace (/\D/, '')"  pattern="[0-9]{2,4}" maxlength="4" id="cvc" placeholder="{!!  trans('details.cvc') !!}" name="cvc" required/>
                <label for="cvc">*</label>

                @if ($errors->has('exp_month'))
                    <div class="help-block">
                        <strong>{{ $errors->first('exp_month') }}</strong>
                    </div>
                @endif
                {!! Form::select('exp_month', $months, null, ['class'=>'double_select']) !!}
                {!! Form::select('exp_year', $years, null, ['class'=>'double_select']) !!}
                <label for="exp_year">*</label>

                <input type="submit" class="button" value="{!!  trans('details.button') !!}"/>
                <p class="cvc-text">{!!  trans('details.cvc-text') !!}</p>
            </form>
            <p class="req">*{!!  trans('login.req') !!}</p>
        </div>
    </div>
</div>