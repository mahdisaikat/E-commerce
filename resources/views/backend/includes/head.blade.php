<base href="./">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
<meta name="description" content="{{ env('DESCRIPTION','description') }}">
<meta name="author" content="{{ env('AUTHOR','author') }}">
<meta name="keyword" content="{{ env('KEYWORDS','keyword') }}">
<title>{{ $configurations['app_name'] ?? config('app.name') }} {{ !empty($title) ? '| ' . $title : '' }}</title>
<link rel="apple-touch-icon" sizes="57x57"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-57x57.png' }}">
<link rel="apple-touch-icon" sizes="60x60"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-60x60.png' }}">
<link rel="apple-touch-icon" sizes="72x72"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-72x72.png' }}">
<link rel="apple-touch-icon" sizes="76x76"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-76x76.png' }}">
<link rel="apple-touch-icon" sizes="114x114"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-114x114.png' }}">
<link rel="apple-touch-icon" sizes="120x120"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-120x120.png' }}">
<link rel="apple-touch-icon" sizes="144x144"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-144x144.png' }}">
<link rel="apple-touch-icon" sizes="152x152"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-152x152.png' }}">
<link rel="apple-touch-icon" sizes="180x180"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/apple-icon-180x180.png' }}">
<link rel="icon" type="image/png" sizes="192x192"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/android-icon-192x192.png' }}">
<link rel="icon" type="image/png" sizes="32x32"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/android-icon-32x32.png' }}">
<link rel="icon" type="image/png" sizes="96x96"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/android-icon-96x96.png' }}">
<link rel="icon" type="image/png" sizes="16x16"
    href="{{ $configurations['app_favicon_link'] ?? 'assets/favicon/android-icon-16x16.png' }}">
<link rel="manifest" href="assets/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<!-- Vendors styles-->
{{--
<link rel="stylesheet" href="node_modules/simplebar/dist/simplebar.css">--}}
{{--
<link rel="stylesheet" href="css/vendors/simplebar.css">--}}
<!-- Main styles for this application-->
@vite(['resources/sass/app.scss', 'resources/js/app.js'])
<!-- We use those styles to show code examples, you should remove them in your application.-->
{{--
<link href="css/examples.css" rel="stylesheet">--}}
{{--<script src="js/config.js"></script>--}}
{{--<script src="js/color-modes.js"></script>--}}
{{--
<link href="node_modules/@coreui/chartjs/dist/css/coreui-chartjs.css" rel="stylesheet">--}}

@stack('styles')

<!-- Custom styles -->
<style>
    .errors {
        color: red;
    }
</style>