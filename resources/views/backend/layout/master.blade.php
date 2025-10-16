<!DOCTYPE html>
<html lang="en">

<head>

    @include('backend.includes.head')

</head>

<body>
    @include('backend.includes.sidebar')

    <div class="wrapper d-flex flex-column min-vh-100">

        <header class="header header-sticky p-0 mb-4">

            @include('backend.includes.navbar')
            @include('backend.includes.header')

        </header>

        <div class="body flex-grow-1">

            <div class="container-lg px-4">

                @yield('content')

            </div>
        </div>

        @include('backend.includes.footer')

    </div>

    @include('backend.includes.foot')

</body>

</html>