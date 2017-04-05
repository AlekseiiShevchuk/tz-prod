<select id="audio_categories_id" name="audio_categories_id">
    {{ $categories }}
    @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ $selected == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
    @endforeach
</select>