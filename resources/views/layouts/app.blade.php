<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title> Beteg elégedettségi kérdőív</title>

  <script src="{{ mix('js/app.js') }}" defer></script>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>
<body>

<div id="app">
  <!-- vuetify -->
  <v-app>
    <v-main>
      @yield('content')
    </v-main>
  </v-app>
</div>

<script>
  window.bladeMode = @json(1);
</script>

</body>
</html>
