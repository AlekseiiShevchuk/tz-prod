<select id="country_id" name="country_id" class="{{ $class }}">
    @foreach($countries as $country)
        <option value="{{ $country->id }}" phonecode="{{ $country->phonecode }}" {{ $selected == $country->id ? 'selected' : '' }}>@if($country->name == '') {!!  trans('membre.country') !!} @else {{ $country->name }} @endif</option>
    @endforeach
</select>