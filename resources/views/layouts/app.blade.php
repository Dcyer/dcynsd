<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $meta_description }}">
    <meta name="author" content="{{ config('blog.author') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('blog.title') }}</title>
    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    <style>
        #gotop {
            display: block;
            width: 60px;
            height: 60px;
            position: fixed;
            bottom: 50px;
            right: 100px;
            border-radius: 10px 10px 10px 10px;
            text-decoration: none;
            display: none;
            background-color: #999999;
        }

        #gotop span {
            display: block;
            width: 60px;
            color: #dddddd;
        }

        #gotop span:hover {
            color: #cccccc;
        }

        #gotop span {
            font-size: 40px;
            text-align: center;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    @include('layouts._header')

    @yield('page-header')

    @yield('content')

    <a id="gotop" href="#">
        <span>▲</span>
    </a>

    @include('layouts._footer')

    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
    <script>
        $(function () {
            $(window).scroll(function () {
                var scrollt = document.documentElement.scrollTop + document.body.scrollTop;
                if (scrollt > 500) {
                    $("#gotop").fadeIn(1);
                } else {
                    // 如果返回或者没有超过,就淡入.必须加上stop()停止之前动画,否则会出现闪动
                    $("#gotop").stop().fadeOut(1);
                }
            });
            // 当点击标签的时候,使用animate在200毫秒的时间内,滚到顶部
            $("#gotop").click(function () {
                $("html,body").animate({scrollTop: "0px"}, 200);
            });
        });
    </script>
</body>
</html>