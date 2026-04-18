<!DOCTYPE html>
<html>
<head><title>Dashboard - MuniciReport</title></head>
<body>
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>