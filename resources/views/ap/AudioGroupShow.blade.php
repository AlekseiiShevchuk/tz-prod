@extends('ap.layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <div class="panel panel-default">

        <div class="panel-body">
            <form id="item_form"
                  action="{{ action('Ap\AudioGroupsController@update', $item->id) }}"
                  method="post">

                <input type="hidden" name="_method" value="PUT">

                {{ csrf_field() }}

                <div class="well">
                    <h2>Main information</h2>

                    @if(Session::has('flash_message'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            {{ Session::get('flash_message') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Title *</label>
                                <input type="text" class="form-control" id="title" name="title"
                                       value="{{ $item->title }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Category *</label>

                                {!! View::make('widgets.InputSelectAudioCategories', ['audio_categories_id' => $item->audio_categories_id]) !!}

                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {!! Form::open(['method' => 'DELETE', 'id' => 'delete_item_form', 'action' => ['Ap\AudioGroupsController@destroy', $item->id]]) !!}
            {!! Form::close() !!}

            {!! Form::open(['method' => 'PUT', 'id' => 'activated_item_form', 'url' => '/ap/groups/activate/' . $item->id, 'style' => 'display: inline']) !!}
            {!! Form::close() !!}
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary" onclick="jQuery('#item_form').submit();">
                Save
            </button>

            @if(!$item->trashed())
                <button class="btn btn-danger" onclick="jQuery('#delete_item_form').submit();">
                    Deactivate
                </button>
            @else
                <button href="" class="btn btn-warning" onclick="jQuery('#activated_item_form').submit();">
                    Activate
                </button>
            @endif

            <a class="btn btn-link" href="{{ url(action('Ap\AudioGroupsController@index')) }}">
                Cancel
            </a>
        </div>
    </div>

@endsection