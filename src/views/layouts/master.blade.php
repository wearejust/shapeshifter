<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<title>Beheer — {{ Config::get('shapeshifter::config.customer') }}</title>
<meta name="description" content="">
<meta name="author" content="wearejust.com">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-title" content="Content">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
<meta name="msapplication-TileImage" content="/apple-touch-icon-precomposed.png">
<meta name="msapplication-TileColor" content="#ffffff">
<script>
var model = '{{ addslashes(get_class($model)) }}';
window.TOUCH=(function(){try{document.createEvent('TouchEvent');return true;}catch(e){return false;}})();
document.documentElement.className=TOUCH?"js touch":"js";
</script>
<link href="/apple-touch-icon-precomposed.png" rel="image_src">
<link href="/apple-touch-icon-precomposed.png" rel="apple-touch-icon-precomposed">
{{ HTML::style('/packages/just/shapeshifter/css/main.css') }}
{{ HTML::style('/packages/just/shapeshifter/css/jquery-ui/jquery-ui-1.10.4.custom.css') }}
{{ HTML::style('/packages/just/shapeshifter/js/jquery-tokeninput/styles/token-input-bootstrap.css') }}
{{ HTML::style('/packages/just/shapeshifter/js/sweetalert/sweet-alert.css') }}

{{-- HTML::style('/packages/just/shapeshifter/css/colorpicker/spectrum.css') --}}
<style>
.cke_contents { height: auto !important; }
.cke_wysiwyg_div { max-height: 600px; min-height: 200px; }
</style>
</head>
<!--[if lte IE 8]><body class="{{ ! $currentUser ? 'login ' : '' }}ie8"><![endif]-->
<!--[if gte IE 9]><!--> <body class="{{ ! $currentUser ? 'login ' : '' }}"><!--<![endif]-->
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
    @if ($mode == 'index' && $preview)
    <p class="section-start section-end" style="bottom: 0; margin: 4px 1.5em; position: fixed; right: 0; z-index: 20;"><a class="btn btn-preview" href="/preview/" target="_blank">Preview</a></p>
    @endif
    <div class="header-bottom">
        <div class="breadcrumbs">
            <ul class="breadcrumbs-list list">
                <li class="breadcrumbs-item">
                    <a class="breadcrumbs-link breadcrumbs-first link-alt" href="/admin">{{ __('breadcrumb.home') }}</a>
                <!--</li>-->
                @foreach ($breadcrumbs as $crumb)
                <li class="breadcrumbs-item">
                    @if ($crumb == end($breadcrumbs))
                    <span class="breadcrumbs-link">{{ $crumb['title'] }}</span>
                    @else
                    <a class="breadcrumbs-link breadcrumbs-link-button link-alt" href="{{ $crumb['url'] }}"><span class="breadcrumbs-link-text">{{ $crumb['title'] }}</span></a>
                    @endif
                <!--</li>-->
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="header">
    <div class="header-middle" style="display: none;">
        <div class="identity">
            <h2 class="system-client">{{ Config::get('shapeshifter::config.customer') }}</h2>
        </div>
    </div>
    <div class="main-nav toggleLeft" id="menu">
        {{ $beforeMenu }}
        <ul class="main-nav-list list group">
            @foreach ($menu as $item)
            <li class="main-nav-item{{ $item['active'] ? ' main-nav-item-active' : '' }}">
                <a class="main-nav-link" href="/admin/{{ $item['url'] }}">{{ $item['title'] }}</a>
                @if (isset($item['children']) && count($item['children']))
                <ul class="sub-list list {{ ! $item['active'] ? 'js-hide' : ''}}">
                    @foreach ($item['children'] as $child)
                    <li class="sub-item">
                        <a class="sub-item-button{{ $child['active'] ? ' sub-item-button-active' : '' }}" href="/admin/{{ $child['url'] }}">{{ $child['title'] }}</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
        {{ $afterMenu }}
    </div>
</div>
<div class="header-top system-account">
    <div class="container">
        <p class="system-user">{{ $currentUser->name }}</p>
        <p class="system-log">
            <a href="{{ route('admin-logout') }}" class="system-log-button"><span class="accessibility">{{ __('user.logout') }}</span></a>
        </p>
    </div>
</div>
<p class="system-name">
    <a href="/admin" class="system-name-button">Content</a>
</p>
@endif
{{ HTML::script("/packages/just/shapeshifter/js/vendor/jquery-1.11.0.min.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/jquery-tokeninput/src/jquery.tokeninput.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/ckeditor/ckeditor.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/sweetalert/sweet-alert.min.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/vendor/jquery-ui.min.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/vendor/jquery.ui.touch-punch.min.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/vendor/jquery.dataTables.min.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/vendor/spectrum.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/vendor/jquery.maskedinput.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/vendor/jquery.form.min.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/vendor/hashchange.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/tracer.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/includes.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/transforms.js") }}
{{ HTML::script("/packages/just/shapeshifter/js/main.js") }}
@if (isset($additionalJS))
    @foreach($additionalJS as $js)
        {{ HTML::script($js) }}
    @endforeach
@endif