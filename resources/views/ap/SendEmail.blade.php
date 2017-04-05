@extends('ap.layouts.app')

@section('content')

    <h1>{{ $title }}</h1>

    @if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>
    @endif

    @if(Session::has('flash_error'))
        <div class="alert alert-danger">
            {{ Session::get('flash_error') }}
        </div>
    @endif

    <form id="item_form" method="post">

        <div class="container">

            {{ csrf_field() }}

            <div class="row">

                <input type="text" class="form-control" name="subject" placeholder="Subject">

            </div>

            <div class="row">

                <label class="radio-inline">
                    <input type="radio" name="group" id="optionsRadios1" value="all" @if ( ! $emails ) checked @endif>
                    All users
                </label>
                <label class="radio-inline">
                    <input type="radio" name="group" id="optionsRadios1" value="client">
                    All clients
                </label>
                <label class="radio-inline">
                    <input type="radio" name="group" id="optionsRadios2" value="partner">
                    All partners
                </label>
                <label class="radio-inline">
                    <input type="radio" name="group" id="optionsRadios2" value="ids" @if ( $emails ) checked @endif>
                    By emails
                </label>


            </div>

            <div class="row">

                <div class="hidden_el">
                    <input type="text" name="email" class="form-control" id="emails_input" value="{{ $emails }}">
                </div>

            </div>
            <div class="row">

                <textarea class="hidden_el" name="email_message" id="email_message" title=""></textarea>

                <div id="editor_div"></div>

            </div>
            <div class="row">
                <button type="submit" class="btn btn-success">Send</button>
            </div>
        </div>

    </form>

    <style>
        .ql-editor {
            min-height: 100px;
        }
        .row{
            padding: 10px 0;
        }
        span.name {
            padding-right: 10px;
        }

        span.email {
            font-size: 11px;
        }

        .selectize-dropdown-content span.label {
            color: #000;
        }
        .hidden_el{
            display: none;
        }
    </style>



    <style>

    </style>

@endsection

@push('scripts')

<script src="//cdn.quilljs.com/1.2.0/quill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/js/standalone/selectize.min.js"></script>

<script>

    var $textArea = $('#email_message');

    var editor = new Quill('#editor_div', {
        theme: 'snow',
        placeholder: 'Email message'
    });

    var container = editor.container.querySelector('.ql-editor');

    container.addEventListener('DOMSubtreeModified', function(e){

        if(e.target.nodeType == 3){
            console.log(container.innerHTML);
            $textArea.val(container.innerHTML);
        }

    }, false);

    $(document).ready(function(){

        openEmails($('input[name="group"][checked]')[ 0 ]);

        $('input[name="group"]').change(function(e){
            openEmails(this);
        });


        var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
            '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

        $('#emails_input').selectize({
            persist: false,
            maxItems: null,
            valueField: 'email',
            labelField: 'name',
            searchField: 'email',
            render: {
                item: function(item, escape){
                    return '<div>' +
                        (item.name && item.name != item.email ? '<span class="name">' + escape(item.name) + '</span>' : '') +
                        (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
                        '</div>';
                },
                option: function(item, escape){
                    var label = item.name || item.email;
                    var caption = item.name ? item.email : null;
                    return '<div>' +
                        '<span class="label">' + escape(label) + '</span>' +
                        (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                        '</div>';
                }
            },
            create: function(input){
                if((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)){
                    return {email: input};
                }
                var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
                if(match){
                    return {
                        email: match[ 2 ],
                        name: $.trim(match[ 1 ])
                    };
                }
                alert('Invalid email address.');
                return false;
            },
            createFilter: function(input){
                var match, regex;

                // email@address.com
                regex = new RegExp('^' + REGEX_EMAIL + '$', 'i');
                match = input.match(regex);
                if(match) return !this.options.hasOwnProperty(match[ 0 ]);

                // name <email@address.com>
                regex = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
                match = input.match(regex);
                if(match) return !this.options.hasOwnProperty(match[ 2 ]);

                return false;
            },
            load: function(query, callback){

                if(!query.length && query.length > 3) return callback();

                $.get('/ap/users/findByEmail', {query: query}, function(d){
                    callback(d);
                }, 'json');

            }
        });

        function openEmails(radioEl){

            if(radioEl.value == 'ids'){
                $('#emails_input').parent().slideDown();
            } else{
                $('#emails_input').parent().slideUp();
            }
        }

    });

</script>

@endpush

@push('css')
<link href="//cdn.quilljs.com/1.2.0/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.bootstrap3.min.css" />
@endpush