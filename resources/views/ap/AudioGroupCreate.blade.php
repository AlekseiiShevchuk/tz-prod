@extends('ap.layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <div class="panel panel-default">

        <div class="panel-body">
            <form id="item_form"
                  action="{{ action('Ap\AudioGroupsController@store') }}"
                  method="post">

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
                                       value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Category *</label>

                                {!! View::make('widgets.InputSelectAudioCategories', ['audio_categories_id' => 0]) !!}

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary" onclick="jQuery('#item_form').submit();">
                Save
            </button>

            <a class="btn btn-link" href="{{ url(action('Ap\AudioGroupsController@index')) }}">
                Cancel
            </a>
        </div>
    </div>

@endsection