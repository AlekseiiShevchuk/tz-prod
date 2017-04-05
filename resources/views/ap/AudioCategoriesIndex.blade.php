@extends('ap.layouts.app')

@section('content')

    <h1>{{$title}}</h1>

    @include('ap.navbar', ['buttons' => $buttons])

    @if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>
    @endif

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="sortable">

        @foreach($items as $item)
            <tr id="{{$item->id}}">
                <td>{{ $item->id }}</td>
                <td><a href="/ap/category/{{$item->id}}/groups">{{ $item->title }}</a></td>
                <td>
                    <a href="{{ action('Ap\AudioCategoriesController@show', $item->id) }}"
                       class="btn btn-default btn-xs"
                       title="Edit"><i class="glyphicon glyphicon-edit"></i></a>

                    @if($item->trashed())
                        {!! Form::open(['method' => 'PUT', 'id' => 'activated_item_form', 'url' => '/ap/categories/activate/' . $item->id, 'style' => 'display: inline']) !!}
                        <button href="" class="btn btn-warning btn-xs" title="Activate">
                            <i class="glyphicon glyphicon-ok-circle"></i>
                        </button>
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['method' => 'DELETE', 'id' => 'deactivated_item_form', 'action' => ['Ap\AudioCategoriesController@destroy', $item->id], 'style' => 'display: inline']) !!}
                        <button href="" class="btn btn-danger btn-xs" title="Deactivate">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                        </button>
                        {!! Form::close() !!}
                    @endif
                </td>
            </tr>

        @endforeach

        </tbody>
    </table>

    @if(count($items) == 0)
        <div class="empty_result">Empty result</div>
    @else

        {{ $items->appends($filter)->render() }}

        @include('ap.layouts.PerPage')

    @endif

@endsection
@push("scripts")
<script type="text/javascript" src="{{ url('/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{csrf_token()}}"
        }
    });
    $("#sortable").sortable({
        revert: true,
        update: update
    });
    function update(){
        var listSortable = $(this).sortable('toArray').toString();
        $.ajax({
            method: "POST",
            url: "/ap/categories/updateOrder",
            data: {sort: listSortable},
            success: function(data){
                console.log(data);
            }
        });
    }
</script>
@endpush