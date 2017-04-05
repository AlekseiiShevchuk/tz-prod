<div class="abonne-title">
    Redirecting to credit card validation page
</div>
<div class="content-white padding-to-footer">
    <div name="membre" class="membre">
        <div class="membre-block-form">
            <p>
                If your browser does not start loading the page,
                press the button below.
                You will be sent back to this site after you
                authorize the transaction.
            </p>
            <form name="ThreeDForm" method="POST" action="{{ $auth->getUrl() }}" class="membre-form">
                <input type="submit" class="button membre-profile_buttom" value="Click Here" style="margin-left: 28%;">
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

