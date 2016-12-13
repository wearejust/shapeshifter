<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<title>Beheer — {{ config('shapeshifter.customer') }}</title>
<meta name="description" content="">
<meta name="author" content="wearejust.com">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-title" content="Content">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
<meta name="msapplication-TileImage" content="/apple-touch-icon-precomposed.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="csrf_token" content="{{ csrf_token() }}">
<script>
var model = '{{ addslashes(get_class($model)) }}';
window.TOUCH=(function(){try{document.createEvent('TouchEvent');return true;}catch(e){return false;}})();
document.documentElement.className=TOUCH?"js touch":"js";
</script>
<link href="/apple-touch-icon-precomposed.png" rel="image_src">
<link href="/apple-touch-icon-precomposed.png" rel="apple-touch-icon-precomposed">
<link href="/packages/just/shapeshifter/css/main.css" rel="stylesheet">
{!! Html::style('/packages/just/shapeshifter/css/jquery-ui/jquery-ui-1.10.4.custom.css') !!}
{!! Html::style('/packages/just/shapeshifter/js/jquery-tokeninput/styles/token-input-bootstrap.css') !!}
{!! Html::style('/packages/just/shapeshifter/css/colorpicker/spectrum.css') !!}
{!! Html::style("/packages/just/shapeshifter/js/colorbox/colorbox.css")  !!}
{!! Html::style("/packages/just/shapeshifter/js/flatpickr/flatpickr.dark.min.css")  !!}
{!! Html::style("/packages/just/shapeshifter/js/selectize/dist/css/selectize.default.css")  !!}
{!! Html::style("/packages/just/shapeshifter/js/sweetalert/sweetalert2.css")  !!}
{!! Html::style("//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css")  !!}

@yield('styles')
<link href="/packages/just/shapeshifter/css/overrides.css" rel="stylesheet">
</head>
<body class="{{ ! $currentUser ? 'login ' : '' }}">
@if ( ! $currentUser)
    @yield('login')
@else
<p class="menu-nav">
    <a class="menu-nav-button"><span class="accessibility">Menu</span></a>
</p>
<div class="content-wrapper">
    <div class="main-content" id="top">
        <div class="page">
            <div class="content">
                <div class="content-body">
                    @yield('content')
                </div>
                <div id="dialog-confirm" style="display: none;">
                    <p>{{ __('dialog.remove') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header">
    <div class="header-middle" style="display: none;">
        <div class="identity">
            <h2 class="system-client">{{ config('shapeshifter.customer') }}</h2>
        </div>
    </div>
    <div class="main-nav toggleLeft" id="menu">
        <ul class="main-nav-list list group">
            @foreach ($menu as $module)
                <li class="main-nav-item">
                    <a class="main-nav-link {{ $module['active'] ? ' main-nav-item-active' : '' }}" href="{{ $module['url'] }}"><i class="fa fa-{{ $module['icon'] }}"></i> &nbsp; {{ $module['name'] }}</a>
                    @if (count($module['children']))
                        <ul class="sub-list list {{ ! $module['active'] ? 'js-hide' : ''}}">
                            @foreach ($module['children'] as $child)
                                <li class="sub-item">
                                    <a class="sub-item-button {{ $child['active'] ? ' sub-item-button-active' : '' }}" href="{{ $child['url'] }}">{{ $child['name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="header-top system-account">
    <div class="container">
        <p class="system-user">{{ $currentUser->getName() }}</p>
        <p class="system-log">
            <a href="{{ route('admin-logout') }}" class="system-log-button"><span class="accessibility">{{ __('user.logout') }}</span></a>
        </p>
    </div>
</div>
<p class="system-name">
    <a href="/admin" class="system-name-button">Content</a>
</p>
@endif

{!!  Html::script("/packages/just/shapeshifter/js/vendor/jquery-1.11.0.min.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/jquery-tokeninput/src/jquery.tokeninput.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/ckeditor/ckeditor.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/ckeditor/adapters/jquery.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/colorbox/jquery.colorbox-min.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/vendor/jquery-ui.min.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/vendor/jquery.ui.touch-punch.min.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/vendor/jquery.dataTables.min.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/vendor/spectrum.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/vendor/jquery.maskedinput.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/vendor/jquery.form.min.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/vendor/hashchange.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/tracer.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/includes.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/transforms.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/flatpickr/flatpickr.min.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/selectize/dist/js/standalone/selectize.min.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/main.js")  !!}
{!!  Html::script("/packages/just/shapeshifter/js/sweetalert/sweetalert2.js")  !!}

@yield('scripts')
