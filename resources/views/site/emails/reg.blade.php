<p>
    Bienvenue,
    <br>
    <br>
    {!! trans('login.email_valid_text') !!}
    <br>
    <br>
    {!! trans('login.email_valid_link') !!} <a href="{{ $url }}">Valider</a>
    <p>Ce lien expirera dans 7 jours.
    Après ce délai, vous devrez vous réinscrire.</p>
    <br>
    <br>
    {!! trans('login.email_valid_footer') !!}
</p>