<div class="abonne-title">
    {!! Auth::user()->is_subscription_renewable ?  trans('renewal.title') : trans('renewal.title_back') !!}
</div>
<div class="content-white">
    <div class="faq">
        <div class="tzinfo-block text-align-center">

            <p class="tzinfo-line"></p>
            <div class="tzinfo-block-text">
                <div class="tzinfo-block-text_text">
                    <div class="renewal-text">
                        {!! Auth::user()->is_subscription_renewable ? trans('renewal.text') : trans('renewal.text_back') !!}
                    </div>
                </div>
            </div>
            <p class="tzinfo-line"></p>
            <div class="renewal">
                @if( Auth::user()->is_subscription_renewable )
                    <a href="{{ URL::previous() }}" class="button-renewal">{!!  trans('renewal.button-1') !!}</a>
                    <a href="{{ url('abonne/renewal/delete') }}" class="button-renewal">{!!  trans('renewal.button-2') !!}</a>
                @else
                    <a href="{{ url('abonne/renewal/activate') }}" class="button-renewal">{!!  trans('renewal.activate') !!}</a>
                    <a href="{{ URL::previous() }}" class="button-renewal">{!!  trans('renewal.no') !!}</a>
                @endif
            </div>

        </div>
    </div>
</div>