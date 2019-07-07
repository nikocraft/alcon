<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laraone | Installation</title>

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/app.css', 'themes/admin_one') }}" rel="stylesheet" type="text/css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token()
        ]); ?>
    </script>
</head>
<body>
    <div id="app" class="install-wrapper">
        <install-wizard/>
    </div>

    @routes
    <script src="{{ mix('js/install.js', 'themes/admin_one') }}"></script>
    @stack('scripts')
</body>
</html>
