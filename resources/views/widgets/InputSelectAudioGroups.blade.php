<select id="audio_groups_id" name="audio_groups_id" class="form-control">
    @foreach($groups as $group)
        <option value="{{ $group->id }}" {{ $selected == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
    @endforeach
</select>