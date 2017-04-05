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
        <tbody id="sortable">

        @foreach($groups as $item)
            <tr id="{{ $item->id }}">
                <td>{{ $item->id }}</td>
                <td><a href="/ap/group/{{$item->id}}/sounds">{{ $item->title }}</a></td>
                <td>{{  $item->category ? $item->category->title : '' }}</td>
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
            url: "/ap/category/{{count($groups) > 0 ? $groups[0]->category->id : ""}}/updateOrderGroup",
            data: {sort:listSortable},
            success:function (data) {
                console.log(data);
            }
        });
    }
</script>
@endpush