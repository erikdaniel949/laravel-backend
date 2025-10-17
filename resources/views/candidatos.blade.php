<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Form Test</title>
</head>
<body>
    <form method="POST" action="{{ url('/candidatos/create') }}">
        @csrf
        <input type="text" name="provincia" placeholder="Provincia">
        <input type="text" name="cargo" placeholder="Cargo">
        <input type="text" name="lista" placeholder="Lista">
        <input type="text" name="nombre" placeholder="nombre">
        <input type="text" name="orden_en_lista" placeholder="orden_en_lista">
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
