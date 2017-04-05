<ul class="pagination per_page">
    @foreach($pers_page as $per_page)
        <li @if($per_page == $current_per_page) class="active" @endif>
            <a href="?per_page={{$per_page}}{{ isset($query) ? '&query='.$query : ''}}{{ isset($role) ? '&role='.$role : ''}}">{{$per_page}}</a>
        </li>
    @endforeach
</ul>