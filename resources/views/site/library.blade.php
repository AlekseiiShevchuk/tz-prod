<div class="modal" id="share-by-email-popup">
			<div class="modal__container">
				<div class="modal__content">
					<div class="modal__header">
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
                <input type="email" name="email" class="share-by-email__input" id="share-by-email__input">
                <div class="share-by-email__btn-group">
                  <input class="share-by-email__submit" type="submit" id="btn-1" value="PARTAGER"/>
                  <input class="share-by-email__submit" type="submit" id="btn-2" value="AJOUTER UNE"/>
                </div>
            </form>
            <div class="modal__msg"></div>
					</div>
				</div>
			</div>
		</div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script type="text/javascript">
    // $(document).on('submit', '#post-form-share', function(e) {
    //   e.preventDefault();
    //   var $form = $(this);
    //   console.log(this);
    //   $.ajax({
    //     url: "share-by-email",
    //     method : "POST",
    //     data:  $form.serialize(),
    //     success: function (data) {
    //       console.log(data);
    //       $(".modal__msg").html("<span class='modal__msg--success'>Nous avons envoyé votre invitation. <br> Merci pour votre partage.</span>");
    //     },
    //     error: function(xhr, ajaxOptions, thrownError) {
    //       console.log('xhr.status: ' + xhr.status);
    //       console.log('thrownError: ' + thrownError);
    //       $(".modal__msg").html("<span class='modal__msg--error''>Nous n’avons pas pu envoyer votre invitation. <br> Veuillez vérifier les informations et essayer une nouvelle fois.</span>");
    //     }
    //   });
    // });

    $(document).on('click', '#btn-1', function(e) {
      e.preventDefault();
      var email = $('#share-by-email__input');
      // var data = {
      //   token: $('input[name="_token"]').val(),
      //   email: e.target.email.value,
      // };
      console.log('email: ' + email.val());

      // $.ajax({
      //   url: "share-by-email",
      //   method : "POST",
      //   data:  $form.serialize(),
      //   success: function (data) {
      //     console.log(data);
      //     $(".modal__msg").html("<span class='modal__msg--success'>Nous avons envoyé votre invitation. <br> Merci pour votre partage.</span>");
      //   },
      //   error: function(xhr, ajaxOptions, thrownError) {
      //     console.log('xhr.status: ' + xhr.status);
      //     console.log('thrownError: ' + thrownError);
      //     $(".modal__msg").html("<span class='modal__msg--error''>Nous n’avons pas pu envoyer votre invitation. <br> Veuillez vérifier les informations et essayer une nouvelle fois.</span>");
      //   }
      // });
    })
    </script>

<div class="abonne-title">
    {!!  trans('library.title') !!}
</div>
<div class="content-white content-library">
    <div class="library">
        <p class="tzinfo-line library-line"></p>
        <div class="tzinfo-block">
            <div class="tzinfo-block-img">
                <img src="src/img/Image_Library_01.jpg" style="object-fit: cover;" width="185px" height="248px">
            </div>
            <div class="tzinfo-block-text">
                <div class="tzinfo-block-text_text">
                    {!!  trans('library.text-1') !!}
                </div>
            </div>
        </div>
        <p class="library-line"></p>
        <ul class="category">
            @foreach ($categories as $category)
                @php($groups = $category->groups)
                @if (!$groups->isEmpty())
                    <li>
                        {{--<div class="category-name first">--}}
                        {{--{{ $category->title }}--}}
                        {{--</div>--}}
                        <input type="checkbox" class="group_name-down category-name" id="category-{{ $category->id }}" @if($category->id == $active_category) checked="checked" @endif/>
                        <label for="category-{{ $category->id }}"
                               class="group_name-down category-name first">{{ $category->title }}</label>
                        <div>
                            <ul class="group_name">
                                @foreach ($groups as $group)
                                    @php($sounds = $group->sounds)
                                    @if (!$sounds->isEmpty())
                                        <li>
                                            <input type="checkbox" class="group_name-down" id="group-{{ $group->id }}"/>
                                            <label for="group-{{ $group->id }}"
                                                   class="group_name-down">{{ $group->title }}</label>
                                            <div>
                                                <ul class="tracks">
                                                    @foreach ($sounds as $sound)
                                                        <li>
                                                            <div class="group_name-track_name">
                                                                {{ $sound->title }}
                                                            </div>
                                                            <div class="group_name-time">
                                                                {{ $sound->getDuration() }}
                                                            </div>

                                                            @if ($sound->isAccess())
                                                                <div class="group_name-controls">
                                                                    <a href="#" a="{{ $sound->url }}"
                                                                       class="group_name-play ms-play" audio="true"
                                                                       audio-name="{{ $sound->title }}"></a>
                                                                    <a href="#" class="group_name-replay"></a>
                                                                    <a href="#" class="icon-share-by-email" popup='share-by-email'></a>
                                                                </div>
                                                            @else
                                                                <div class="group_name-controls blocked">
                                                                    <a href="#" class="group_name-play ms-play {{ Auth::user()->is_email_valid ? 'only_for_subscribers' : 'verif_email_sound' }}"></a>
                                                                    <a href="#" class="group_name-replay {{ Auth::user()->is_email_valid ? 'only_for_subscribers' : 'verif_email_sound' }}"></a>
                                                                </div>
                                                            @endif

                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
