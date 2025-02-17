<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title> Beteg elégedettségi kérdőív</title>

  <script src="{{ asset('js/app.js') }}" defer></script>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
<div id="app">
  <app></app>
</div>

<script>
  window.bladeMode = @json(0);
</script>
</body>
</html>
