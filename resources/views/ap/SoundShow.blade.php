@extends('ap.layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <div class="panel panel-default">

        <div class="panel-body">
            <form id="item_form"
                  action="{{ action('Ap\SoundController@update', $item->id) }}"
                  method="post"
                  enctype="multipart/form-data">

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

                    <div class="form-group">
                        <audio controls>
                            <source src="{{ $item->url }}" type="audio/mpeg">
                        </audio>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Group *</label>

                                {!! View::make('widgets.InputSelectAudioGroups', ['audio_groups_id' => $item->audio_groups_id]) !!}

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Is free *</label>
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="radio" name="is_free" value="0" @if(!$item->is_free)checked @endif>
                                    No
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_free" value="1" @if($item->is_free)checked @endif>
                                    Yes
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {!! Form::open(['method' => 'DELETE', 'id' => 'delete_item_form', 'action' => ['Ap\SoundController@destroy', $item->id]]) !!}
            {!! Form::close() !!}

            {!! Form::open(['method' => 'PUT', 'id' => 'activated_item_form', 'url' => '/ap/sounds/activate/' . $item->id, 'style' => 'display: inline']) !!}
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

            <a class="btn btn-link" href="{{ url(action('Ap\SoundController@index')) }}">
                Cancel
            </a>
        </div>
    </div>

@endsection