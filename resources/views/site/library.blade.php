<div class="abonne-title">
    {!!  trans('library.title') !!}
</div>
<div class="content-white content-library">
    <div class="library">
        <p class="tzinfo-line library-line"></p>
        <div class="tzinfo-block tzinfo-block_min-width-meditation">
            <div class="tzinfo-block-img">
                <img src="src/img/image-4.jpg">
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