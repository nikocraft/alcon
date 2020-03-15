<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @yield('title')
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/auth.css', 'laraone') }}"></link>

    <script>
        window.authPage = []
        window.authPage.logoType = "{{ get_website_setting('website.adminAuthPage.logoType') }}"
        window.authPage.logoImage = "{{ get_website_setting('website.adminAuthPage.logoImage') }}"
        window.authPage.logoText = "{{ get_website_setting('website.adminAuthPage.logoText') }}"
    </script>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body>
    <div id="auth">
        @yield('content')
    </div>

    @routes
    <script src="/js/lang.js"></script>
    <script src="{{ mix('js/auth.js', 'laraone') }}"></script>
    @stack('scripts')
</body>
</html>
