<p>
    Bienvenue, {{ $name }}
    <br>
    <br>
    L’équipe de Turbulence Zéro vous a offert un abonnement pour que vous puissiez découvrir les bienfaits de la méditation et notre site.
    @if(!is_null($subscribe_access_to))
    <br>
    <br>
    Votre abonnement se terminera le {{ $subscribe_access_to }}
    @endif
    <br>
    <br>
    Pour en profitez cliquez sur ce lien <a href="{{ $url }}">Aller sur le site</a>
    <br>
<p>
    Voici vos informations de connexion :
</p>
<p>
    {{ trans('app.email') }} : {{ $email }}
    <br>
    {{ trans('app.password') }} : {{ $password }}
</p>
    <br>
Merci de votre confiance.
<br>
L’équipe de Turbulence Zéro
</p>