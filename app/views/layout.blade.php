<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimal-ui">
  <title>Zulkifli's Family</title>
  {{ stylesheet_link_tag() }}
  {{ javascript_include_tag() }}
</head>
<body>
  @if(isset($user))
    @include('topmenu')
  @endif
  @yield('content')
</body>
</html>