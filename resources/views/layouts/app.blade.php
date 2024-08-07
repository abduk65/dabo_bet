<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hope UI | Responsive Bootstrap 5 Admin Dashboard Template</title>

    @include('layouts.styles')
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-WNGH9RL');
        window.tag_manager_event = 'dashboard-free-preview';
        window.tag_manager_product = 'HopeUI';
    </script>
    <!-- End Google Tag Manager -->
</head>

<body class="uikit " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    @include('layouts.sidebar')
    <main class="main-content">

        @yield('content')

        <!-- Footer Section Start -->

    </main>
    <footer class="footer">
        @include('layouts.footer')
    </footer>

    @yield('scripts')
    @include('layouts.scripts')

</body>

</html>
