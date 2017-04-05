<style>

    h1{
        text-align: center;
    }

    th {
        font-weight: 900;
        background: #6c6c6c;
        color: #fff;
    }

    th, td {
        border: solid 1px #440000;
        padding: 10px;
        text-align: center;
    }

</style>
<table style="width: 100%">
    <tr style="width: 100%">
        <td style="width: 100%;border:0px;">
            <img src="{{ public_path('src/img/logo-blue.png') }}" alt="Logo" width="70"/>
        </td>
    </tr>
</table>


@if($title)
    <h1>{{ $title }}</h1>
@endif

<br>

<table cellspacing="-1" style="width: 100%;font-family: freeserif;">
    <tr>
        @foreach ($table['header'] as $key=>$item)
            <th style="width: {{ $table['width'][$key] }}%;">{{ $item }}</th>
        @endforeach
    </tr>

    @foreach ($table['body'] as $row)
        <tr>
            @foreach ($row as $key=>$data)
                <td style="width: {{ $table['width'][$key] }}%;">{{ $data }}</td>
            @endforeach
        </tr>
    @endforeach
</table>