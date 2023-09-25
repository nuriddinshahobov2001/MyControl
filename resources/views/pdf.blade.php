<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laravel PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<h2 class="mb-3 text-center">Акт Сверки</h2>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Расход</th>
        <th>Приход</th>
        <th>Автор</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($histories as $row)
        <tr>
            <td>{{ $row->date }}</td>
            @if($row->type == 'debit')
            <td>{{ $row->summa }}</td>
            @else
            <td>{{ $row->summa }}</td>
            @endif
            <td>{{ $row->author_id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
