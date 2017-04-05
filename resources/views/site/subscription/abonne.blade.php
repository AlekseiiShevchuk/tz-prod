<div class="abonne-title">
    {!!  trans('abonne.title') !!}
</div>
<div class="content-white">
    @if(Session::has('flash_message'))
        <div class="alert membre-block-form {{ Session::has('flash_message_type') ? Session::get('flash_message_type') : '' }}">
            {{ Session::get('flash_message') }}
        </div>
    @endif

    <div class="abonne">
        <div class="block-buy">
            <div class="block-buy-wrapper">
                @foreach($plans as $plan)
                    <div class="block-buy_one">
                        <p class="block-buy_many">
                            {{ $plan->getFormatEuroPricePerMonth() }} / {!!  trans('abonne.month') !!}
                        </p>
                        <p class="block-buy_time">
                            <br>
                            {!!  trans_choice('abonne.months', $plan->countMonth) !!}
                        </p>
                        <p class="block-buy_limit">
                            {!!  trans('abonne.limit') !!}
                        </p>
                        <div class="sale-finish">
                            {!!  trans('abonne.current') !!}
                        </div>
                        <a href="{{ url( $details_url . $plan->id ) }}" ajax="true" class="button-buy {{ Auth::user()->is_email_valid == '1' ?: 'verif_email' }}">
                            {!!  trans('abonne.buy') !!}
                        </a>
                        <p class="block-buy_total">
                            {!!  trans('abonne.total') !!}{{ $plan->getFormatEuroPrice() }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-abone">{!!  trans('abonne.text') !!}</div>
    </div>
</div>