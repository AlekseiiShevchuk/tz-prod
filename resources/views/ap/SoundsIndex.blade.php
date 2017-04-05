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
            <th>Group</th>
            <th>Category</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>

                <td>{{ $item->group->title }}</td>
                <td>{{ $item->group->category ? $item->group->category->title : '' }}</td>
                <td>
                    <audio controls preload="none">
                        <source src="{{ $item->url }}" type="audio/mpeg">
                    </audio>
                </td>
                <td>
                    <a href="{{ action('Ap\SoundController@show', $item->id) }}" class="btn btn-default btn-xs"
                       title="Edit"><i class="glyphicon glyphicon-edit"></i></a>

                    @if($item->trashed())
                        {!! Form::open(['method' => 'PUT', 'id' => 'activated_item_form', 'url' => '/ap/sounds/activate/' . $item->id, 'style' => 'display: inline']) !!}
                        <button href="" class="btn btn-warning btn-xs" title="Activate">
                            <i class="glyphicon glyphicon-ok-circle"></i>
                        </button>
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['method' => 'DELETE', 'id' => 'deactivated_item_form', 'action' => ['Ap\SoundController@destroy', $item->id], 'style' => 'display: inline']) !!}
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

        @if (!empty($items))
            {{ $items->appends($filter)->render() }}
        @endif

        @include('ap.layouts.PerPage')

    @endif

@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){

        $('audio').on('play', function(){
            $('audio').not(this).each(function(){
                this.pause();
            });
        });

    });
</script>
@endpush