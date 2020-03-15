<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laraone CMS</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/admin.css', 'laraone') }}" type="text/css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'userRoles' => Auth::user()->user_roles,
            'userPermissions' => Auth::user()->user_permissions,
            'menuTree' => $menuTree,
            'contentTypesList' => $contentTypesList
        ]); ?>
    </script>
    {{-- <link href="{{ asset('images/icon.png') }}" rel="shortcut icon" type="image/png"/> --}}
</head>
<body>
    <div id="app"></div>

    @routes
    <script src="/js/lang.js"></script>
    <script src="{{ mix('js/admin.js', 'laraone') }}"></script>
</body>
</html>
