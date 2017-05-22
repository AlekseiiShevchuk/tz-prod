<div class="modal" id="share-by-email">
			<div class="modal__container">
				<div class="modal__content">
					<div class="modal__header">
            <button type="button" class="modal__close" onclick="closeModalShare()">X</button>
						<h2 class="modal__title">Partagez votre découverte.</h2>
					</div>
					<div class="modal__body">
                        <div class="footer_soc-net">

                            <a href="https://plus.google.com/share?url={{env('APP_URL')}}?hl={{App::getLocale()}}&text=La méditation est un exercice, rien d’autre.
Venez découvrir gratuitement la façon la plus facile d’appréhender la méditation, sans gourou ni cours, tout simplement avec Turbulence Zéro.  Nous vous proposons des séries d’exercices pratiques et de partager avec nous votre expérience.">
                                <img src="/src/img/gog.png" alt="Share on Google+"/>
                            </a>
                            <a href="http://www.facebook.com/sharer/sharer.php?u={{env('APP_URL')}}&text=La méditation est un exercice, rien d’autre.
Venez découvrir gratuitement la façon la plus facile d’appréhender la méditation, sans gourou ni cours, tout simplement avec Turbulence Zéro.  Nous vous proposons des séries d’exercices pratiques et de partager avec nous votre expérience.">
                                <img src="/src/img/facebook2.png">
                            </a>
                        </div>




            <form method="post" id="post-form-share" class="membre-form crop share-by-email" role="form">
                {{ csrf_field() }}
                <input type="email" name="email" class="share-by-email__input" id="share-email" required="required" placeholder="Entrez l’adresse email de la personne que vous voulez inviter.">
                <div class="share-by-email__btn-group">
                  <input class="share-by-email__submit" type="submit" id="btn-2" value="AJOUTER UNE AUTRE PERSONNE"/>
                  <input class="share-by-email__submit2" type="submit" id="btn-1" value="ENVOYER"/>
                </div>
                <!-- <div class="share-by-email__btn-group"><div class='share-by-email__submit' onClick='closePopup()'>Close</div><div class='share-by-email__submit'>Send</div></div> -->
            </form>
            <div class="modal__msg"></div>
					</div>
				</div>
			</div>
		</div>
    <script type="text/javascript">
      function openModalShare() {
				console.log('openModalShare');
        $('body').addClass('modal--open');
 		    $('.modal').addClass('is-open');
      }

      function closeModalShare() {
				console.log('closeModalShare');
        $('body').removeClass('modal--open');
 		    $('.modal').removeClass('is-open');
      }

      $(document).on('click', '#btn-1', function(e) {
        var $form = $('#post-form-share');
					e.preventDefault();
	        $.ajax({
	          url: "share-by-email",
	          method : "POST",
	          data:  $form.serialize(),
	          success: function (data) {
							  e.preventDefault();
	            // console.log(data);
	            $(".modal__msg").html("<span class='modal__msg--success'>Nous avons envoyé votre invitation. <br> Merci pour votre partage.</span>");
						 setTimeout(function(){
	             closeModalShare();
							 $('#share-email').val('');
							 $(".modal__msg").html("");
	            }, 2000);
	          },
	          error: function(xhr, ajaxOptions, thrownError) {
	            console.log('xhr.status: ' + xhr.status);
	            console.log('thrownError: ' + thrownError);
	            $(".modal__msg").html("<span class='modal__msg--error''>Nous n’avons pas pu envoyer votre invitation. <br> Veuillez vérifier les informations et essayer une nouvelle fois.</span>");
	          }
	        });
      });

      $(document).on('click', '#btn-2', function(e) {
        e.preventDefault();
        var $form = $('#post-form-share');
        // console.log($form.serialize());
        $.ajax({
          url: "share-by-email",
          method : "POST",
          data:  $form.serialize(),
          success: function (data) {
            // console.log(data);
            $(".modal__msg").html("<span class='modal__msg--success'>Nous avons envoyé votre invitation. <br> Merci pour votre partage.</span>");
 	          $('#share-email').val('');
            setTimeout(function(){
                $(".modal__msg").html("");
             }, 2000);
          },
          error: function(xhr, ajaxOptions, thrownError) {
            console.log('xhr.status: ' + xhr.status);
            console.log('thrownError: ' + thrownError);
            $(".modal__msg").html("<span class='modal__msg--error''>Nous n’avons pas pu envoyer votre invitation. <br> Veuillez vérifier les informations et essayer une nouvelle fois.</span>");
          }
        });

      });
      </script>
