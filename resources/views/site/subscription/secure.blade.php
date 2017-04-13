<div class="abonne-title">
    Nous attendons la validation de votre paiement.
</div>
<div class="content-white padding-to-footer">
    <div name="membre" class="membre">
        <div class="membre-block-form">
            <p>
                Veuillez patienter quelques instants.
                Si la confirmation ne s'affiche pas sur l'écran, merci de cliquer sur le bouton ci-dessous.
            </p>
            <form name="ThreeDForm" method="POST" action="{{ $auth->getUrl() }}" class="membre-form">
                <input type="submit" class="button membre-profile_buttom" value="RETOUR" style="margin-left: 28%;">
                <input type="hidden" name="PaReq" value="{{ $auth->getData() }}"/>
                <input type="hidden" name="TermUrl" value="{{ $callbackUrl }}"/>
                <input type="hidden" name="MD" value="{{ $identifier }}"/>
            </form>
            <script type="text/javascript">
                // Make the form post as soon as it has been loaded.
                document.ThreeDForm.submit();
            </script>
        </div>
    </div>
</div>

