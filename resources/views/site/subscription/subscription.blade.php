<div class="abonne-title">
    {!!  trans('subscription.title') !!}
</div>
<div class="content-white">
    <div class="faq">

        <div class="block-subscr">
            @if(count($payments) > 0)
                @foreach($payments as $payment)
                    <p class="title">{!!  trans('subscription.abonne') !!}</p>
                    <p>{!!  trans('subscription.type', [ 'months' => $payment->plan()->countMonth ]) !!}</p>
                    <p>{!!  trans('subscription.start-date', ['date' => $payment->start_access_date->format('M d, Y')]) !!}</p>
                    @if ($payment == $payments[count($payments) - 1] && $payment->is_renewable && Auth::user()->is_subscription_renewable )
                        <p class="last">{!!  trans('subscription.next-payment', ['date' => $payment->end_access_date->format('M d, Y')]) !!}</p>
                    @else
                        <p class="last">{!!  trans('subscription.end-date', ['date' => $payment->end_access_date->format('M d, Y')]) !!}</p>
                    @endif
                @endforeach
            @endif
            <div class="block-button">
                <a ajax="true" href="{{ url('/abonne') }}" class="subscr-button">{!!  trans('subscription.button-1') !!}</a>
                {{--<a href="#" class="subscr-button">{!!  trans('subscription.button-2') !!}</a>--}}
            </div>

            @if(count($payments) > 0)
                @if($payments[count($payments) - 1]->is_renewable)
                    <div class="abone-info_links">
                        <a href="{{ url('/abonne/renewal') }}">{!! Auth::user()->is_subscription_renewable ?  trans('abonne.annuler') : trans('abonne.activate') !!}</a>
                    </div>
                @endif
            @endif

        </div>
        @if(count($gifts) > 0)
        <div class="block-subscr">
            <p class="tzinfo-block-text_title">{!! trans('abonne.gifts') !!}</p>
                @foreach($gifts as $gift)
                    <p class="title">{!!  trans('subscription.abonne') !!}</p>
                    <p>{!!  trans('abonne.user') !!} {{ $gift->payer()->name }} {{ $gift->payer()->surname }}</p>
                    <p>{!!  trans('subscription.type', [ 'months' => $gift->plan()->countMonth ]) !!}</p>
                    <p>{!!  trans('subscription.start-date', ['date' => $gift->start_access_date->format('M d, Y')]) !!}</p>
                    <p class="last">{!!  trans('subscription.end-date', ['date' => $gift->end_access_date->format('M d, Y')]) !!}</p>
                @endforeach
            <div class="block-button">
                <a ajax="true" href="{{ url("/abonne/invite")  }}" class="subscr-button">{{ trans('abonne.abonne_invite') }}</a>
            </div>
        </div>
        @endif
    </div>
</div>