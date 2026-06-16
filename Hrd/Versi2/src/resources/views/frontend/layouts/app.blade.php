<!DOCTYPE html>
<html lang="en">
    @include('frontend.partials.head')
    <body class="index-page">
        @include('frontend.partials.nav')

        <main class="main">
            @yield('content')
        </main>

        @include('frontend.partials.bottom')

        @include('frontend.partials.script')
    </body>
</html>
