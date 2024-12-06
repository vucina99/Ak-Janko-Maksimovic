<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 10px;
        }
        .table-container {
            overflow-x: auto;
            width: 100%;
        }
        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 10px;
            word-wrap: break-word;
            white-space: pre-wrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 11%;

        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h4>{{ $title }}</h4> <br>
<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>PRISUSTVO</th>
            <th>VREME</th>
            <th>INSTITUCIJA</th>
            <th>BROJ SUDNICE</th>
            <th>STRANKA 1</th>
            <th>STRANKA 2</th>
            <th>BROJ U KANCELARIJI</th>
            <th>P BROJ</th>
            <th>ZAPOSLENI</th>
        </tr>
        </thead>
        <tbody>
        @forelse($content as $c)
            <tr>
                <td>{{ $c->is_finished ? "DA" : "NE" }}</td>
                <td>{{ $c->time }}</td>
                <td>{{ $c->institution ? $c->institution->name : '' }}</td>
                <td>{{ $c->courtroom_number }}</td>
                <td>{{ $c->prosecutor }}</td>
                <td>{{ $c->defendants }}</td>
                <td>{{ $c->number_office }}</td>
                <td>{{ $c->archive }}</td>
                <td>{{ $c->user ? $c->user->name : '' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align: center;">
                    Nema prikaza ročišta za izabrani dan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
