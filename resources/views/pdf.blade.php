<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laravel PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<h2 class="mb-3 text-center">Akt sverki</h2>
<p class="mb-2 text-center">za period s {{ $from }} po {{ $to }}</p>
<p>Client: {{ $client }}</p>
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
