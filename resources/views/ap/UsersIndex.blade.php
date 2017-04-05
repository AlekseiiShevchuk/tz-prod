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

            <th>
                @if(count($items) != 0)
                    <input type="checkbox" id="user_all"/>
                @endif
            </th>

            <th>ID</th>
            <th>Name</th>
            <th>Email</th>

            @if($role == 'partner')
                <th>Percent</th>
                <th title="Affiliate ID">AID</th>
                <th title="Affiliate URL">URL</th>
            @endif

            <th>Registration</th>

            @if($role == 'client')
                <th>Subscribe to</th>
            @endif

            <th class="three-actions"></th>
        </tr>
        </thead>
        <tbody>

        @foreach($items as $item)
            <tr>
                <th><input type="checkbox" id="user_{{ $item->id }}" value="{{ $item->id }}"/></th>

                <td>{{ $item->id }}</td>
                <td>{{ $item->name }} {{ $item->surname }}</td>
                <td>{{ $item->email }}</td>

                @if($role == 'partner')
                    <td>{{ $item->percent }}</td>
                    <td>{{ $item->aid }}</td>
                    <td><a href="{{ url($item->affiliateUrl) }}" target="_blank">{{ $item->affiliateUrl }}</a></td>
                @endif

                <td>{{ $item->created_at }}</td>

                @if($role == 'client')
                    <td>
                        {{ $item->subscribe_access_to != '-0001-11-30 00:00:00' ? $item->subscribe_access_to->format('Y-m-d') : '' }}
                    </td>
                @endif

                <td>
                    <a href="{{ action('Ap\UsersController@show', $item->id) }}" class="btn btn-default btn-xs"
                       title="Edit"><i class="glyphicon glyphicon-edit"></i></a>

                    <a href="{{ action('Ap\UsersController@sendEmail', ['emails' => $item->email ]) }}"
                       class="btn btn-primary btn-xs"
                       title="Send Email"><i class="glyphicon glyphicon-send"></i></a>

                    @if($item->trashed())
                        {!! Form::open(['method' => 'PUT', 'id' => 'activated_item_form', 'url' => '/ap/users/activate/' . $item->id, 'style' => 'display: inline']) !!}
                        <button href="" class="btn btn-warning btn-xs" title="Activate">
                            <i class="glyphicon glyphicon-ok-circle"></i>
                        </button>
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['method' => 'DELETE', 'id' => 'deactivated_item_form', 'action' => ['Ap\UsersController@destroy', $item->id], 'style' => 'display: inline']) !!}
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

    <script>
        $(document).ready(function(){

            $('.navbar-btn.multiply_send_handler').click(function(e){

                var ids = [];

                $('th input[type="checkbox"]:checked').each(function(){
                    var val = parseInt(this.value);
                    if(val) ids.push(val);
                });

                location.href = this.href + '?ids=' + ids.join(',');
                return false;
            });

            $('#user_all').change(function(){
                $('th input[type="checkbox"]').prop('checked', $(this).prop('checked'));
            });

        });
    </script>

@endsection