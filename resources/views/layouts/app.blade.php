<!doctype html>
<html lang="en" dir="ltr">

<head>
    <title>@yield('title', 'Creative Mind Task')</title>

    @include('layouts.head')
</head>

<body>

    @include('layouts.navbar')
    <div id="content-page" class="content-page">
        <div class="container-fluid relative">
            @yield('content')
        </div>
    </div>
    @include('layouts.footer')

</body>

</html>
