<div class="abonne-title">
    {!!  trans('invite.title') !!}
</div>
<div class="content-white">
    <div class="invite">
        <div class="invite-block">
            <div class="invite-img">
                <div class="tzinfo-block-img ">
                    <img src="/src/img/present.jpg">
                </div>
                {!!  trans('invite.text') !!}
            </div>
            <p class="tzinfo-line"></p>
            <form action="{{ url("/abonne/invite/save") }}" method="post" class="membre-form invite-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="text" name="name" placeholder="{!!  trans('invite.name') !!}" value="{{ Auth::user()->name }}"/>
                <input type="text" name="surname" placeholder="{!!  trans('invite.surname') !!}" value="{{ Auth::user()->surname }}"/>
                <input type="text" name="email" placeholder="{!!  trans('invite.email') !!}" value="{{ Auth::user()->email }}"/>
                <p class="invite-title">{!!  trans('invite.text-of-box') !!}</p>
                <div class="invite-border">
                    <input type="text" name="name-friend" placeholder="{!!  trans('invite.name-friend') !!}" required/>
                    <input type="text" name="surname-friend" placeholder="{!!  trans('invite.surname-friend') !!}" required/>
                    <input type="text" name="email-friend" placeholder="{!!  trans('invite.email-friend') !!}" required/>
                    <p>{!!  trans('invite.text-of-discr') !!}</p>
                    <textarea name="message"></textarea>
                </div>
                <input type="submit" class="button" value="{!!  trans('invite.button') !!}"/>
                <p class="tzinfo-line"></p>
            </form>
        </div>

    </div>
</div>