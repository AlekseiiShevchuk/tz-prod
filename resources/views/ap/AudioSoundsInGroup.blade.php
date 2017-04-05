@extends('ap.layouts.app')

@section('content')

    <h1>{{$title}}</h1>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Group</th>
                <th>Category</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody id="sortable">

            @foreach($sounds as $item)
                <tr id="{{$item->id}}">
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>

                    <td><a href="/ap/category/{{$item->group->category->id}}/groups">{{ $item->group->title }}</a></td>
                    <td><a href="/ap/categories">{{ $item->group->category ? $item->group->category->title : '' }}</a></td>
                    <td>
                        <audio controls preload="none">
                            <source src="{{ $item->url }}" type="audio/mpeg">
                        </audio>
                    </td>
                    <td>
                        @if($item->trashed())
                            deleted
                        @else
                            active
                        @endif
                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>


@endsection
@push("scripts")
<script type="text/javascript" src="{{ url('/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{csrf_token()}}"
        }
    });
    $( "#sortable" ).sortable({
        revert: true,
        update: update
    });
    function update(){
        var listSortable = $(this).sortable('toArray').toString();
        $.ajax({
            method: "POST",
            url: "/ap/group/{{count($sounds) > 0 ? $sounds[0]->group->id : ""}}/updateOrderSounds",
            data: {sort:listSortable},
            success:function (data) {
                console.log(data);
            }
        });
    }
</script>
@endpush