<p>
<p>
    Bonjour {{ $data['name-friend'] }}
</p>
<p>
    {{ $data['name'] }} vous a offert un abonnement à Turbulence Zéro.
</p>
<p>
    Cet abonnement se terminera le {{ $end_access_date }}.
</p>
<p>
    Pour en profitez cliquez sur ce lien : <a href="{{ $url }}">Aller sur le site.</a>
</p>
<p>
    Voici vos informations de connexion :
</p>
<p>
    {{ trans('app.email') }} : {{ $data['email-friend'] }}
@if(!empty($password))
    <br>
    {{ trans('app.password') }} : {{ $password }}
@endif
</p>
<p>
    Merci de votre confiance. <br>
    L’équipe de Turbulence Zéro
</p>
</p>