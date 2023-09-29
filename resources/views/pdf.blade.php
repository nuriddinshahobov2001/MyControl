<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Акт сверки</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=DejaVu+Sans">
</head>
<style>
    body {
        font-family: 'Arial Unicode MS', sans-serif;
    }
</style>

<body>
<h2 class="mb-3 text-center">Akt Sverki</h2>
<p class="mb-2 text-center">za period s {{ $from }} ot {{ $to }}</p>
<p>Klient: {{ $client->fio   }} </p>
<p>Dolg na nachalo: {{ number_format($debt_at_begin, 2) }}  </p>
<p>Dolg na konets: {{ number_format($res, 2) }} </p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Date</th>
        <th>Rasxod</th>
        <th>Prixod</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($histories as $row)
        <tr>
            <td>{{ $row->date }}</td>
            @if($row->type == 'debit')
                <td>{{ $row->summa }}</td>
            @else
                <td></td>
            @endif
            @if($row->type == 'credit')
                <td>{{ $row->summa }}</td>
            @else
                <td></td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
