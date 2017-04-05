@extends('ap.layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <div class="panel panel-default">

        <div class="panel-body">
            <form id="item_form"
                  action="{{ action('Ap\SoundController@store') }}"
                  method="post"
                  enctype="multipart/form-data">

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

                    <div class="form-group">
                        <label for="audio_file">File name *</label>
                        {{--<input type="file" id="audio_file" name="audio_file" accept="audio/mpeg" />--}}
                        <input type="text" class="form-control" id="audio_file_name" name="audio_file_name" required/>
                        {{--<p class="help-block">Select mp3 file</p>--}}
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Group *</label>

                                {!! View::make('widgets.InputSelectAudioGroups', ['audio_groups_id' => 0]) !!}

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <label>Is free *</label>
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="radio" name="is_free" value="0" checked >
                                    No
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_free" value="1">
                                    Yes
                                </label>
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

            <a class="btn btn-link" href="{{ url(action('Ap\SoundController@index')) }}">
                Cancel
            </a>
        </div>
    </div>

@endsection