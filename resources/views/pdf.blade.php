<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laravel PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<h2 class="mb-3">Customer List</h2>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Name</th>
        <th>E-mail</th>
        <th>Phone</th>
        <th>DOB</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $row)
        <tr>
            <td>{{ $row->summa }}</td>
            <td>{{ $row->date }}</td>
            <td>{{ $row->client_id }}</td>
            <td>{{ $row->author_id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
