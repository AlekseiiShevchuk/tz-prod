<p>
<p>
    Bonjour {{ $user->name }},
</p>
    <p>
        Nous avons bien reçu votre paiement de {{ $plan->getFormatEuroPrice() }}.
    </p>
<p>
    Votre abonnement est actif.<br>
    Il se terminera le {{ $end_access_date }}
</p>
<p>
    L’équipe de Turbulence Zéro
</p>

</p>