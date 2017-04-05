@extends('ap.layouts.app')

@section('content')

    <div class="container">

        <h1>{{ $title }}</h1>

        <br>

        <form action="{{ action('Ap\PartnerReportingController@generate') }}" target="_blank">

            <div class="row">

                <label>File type:</label>

                <label class="radio-inline">
                    <input type="radio" name="extension" value="xlsx">
                    XLSX
                </label>
                <label class="radio-inline">
                    <input type="radio" name="extension" value="csv" checked>
                    CSV
                </label>
                <label class="radio-inline">
                    <input type="radio" name="extension" value="pdf">
                    PDF
                </label>

            </div>

            <br>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">From</label>
                        <input type="text" datetimepicker="true" class="form-control" id="from" name="from" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">To</label>
                        <input type="text" datetimepicker="true" class="form-control" id="to" name="to" value="">
                    </div>
                </div>
                @if(Auth::user()->isAdmin())
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Filter</label>
                            <div class="form-group" style="margin-top: 7px;">
                                <label class="radio-inline">
                                    <input type="radio" name="filter" value="all" checked>
                                    All
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="filter" value="all_partners">
                                    All partners
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="filter" value="partner">
                                    Partner
                                </label>
                            </div>
                        </div>
                        <div class="form-group select-part" style="display: none">
                            <label for="name">Select partner</label>
                            <select class="form-control" name="partner_aids[]" id="partner_aids" multiple>
                                @foreach($partners as $partner)

                                    <option value="{{ $partner->aid }}">{{ $partner->email }} (aid:{{ $partner->aid }})
                                    </option>

                                @endforeach

                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <button type="submit" class="btn btn-success">Create</button>
            </div>
        </form>

    </div>

@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('input[name="filter"]').change(function(e){
            if(this.value == 'partner') $('.select-part').show();
            else $('.select-part').hide();
        });
    });
</script>
@endpush