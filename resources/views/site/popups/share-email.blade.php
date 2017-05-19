<div class="modal" id="share-by-email">
			<div class="modal__container">
				<div class="modal__content">
					<div class="modal__header">
            <button type="button" class="modal__close" onclick="closeModalShare()">X</button>
						<h2 class="modal__title">AJOUTER UNE AUTRE PERSONNE - ENVOYER</h2>
					</div>
					<div class="modal__body">
            <p class="modal__text">Entrez l’adresse email de la personne avec qui vous voulez partager votre expérience. </p>
            <p class="modal__text">RECIPIENT EMAIL BOX</p>
            <p class="modal__text">
              AJOUTER UNE AUTRE PERSONNE - ENVOYER (buttons text) <br>
              - If they click on AJOUTER UNE...it sends out the mail to the first recipient, and propose the same RECIPIENT EMAIL BOX again for them to add one more person.<br>
              - If they click on PARTAGER, it sends out the email and closes the pop up.<br>
            </p>
            </p>
            <form method="post" id="post-form-share" class="membre-form crop share-by-email" role="form">
                {{ csrf_field() }}
                <input type="text" name="email" class="share-by-email__input" id="share-email">
                <div class="share-by-email__btn-group">
                  <input class="share-by-email__submit" type="submit" id="btn-1" value="PARTAGER"/>
                  <input class="share-by-email__submit" type="submit" id="btn-2" value="AJOUTER UNE"/>
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
        $('body').addClass('modal--open');
 		    $('.modal').addClass('is-open');
      }

      function closeModalShare() {
        $('body').removeClass('modal--open');
 		    $('.modal').removeClass('is-open');
      }

      $(document).on('click', '#btn-1', function(e) {
        e.preventDefault();
        var $form = $('#post-form-share');
        // console.log($form.serialize());
        $.ajax({
          url: "share-by-email",
          method : "POST",
          data:  $form.serialize(),
          success: function (data) {
            console.log(data);
            $(".modal__msg").html("<span class='modal__msg--success'>Nous avons envoyé votre invitation. <br> Merci pour votre partage.</span>");
           setTimeout(function(){
             $('.modal').hide();
               $('body').removeClass('modal--open');
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
            console.log(data);
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
