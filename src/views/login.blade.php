<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<title>Content — {{ Config::get('shapeshifter::config.customer') }}</title>
<meta content="" name="description">
<meta content="wearejust.com" name="author">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
<meta name="msapplication-TileImage" content="/apple-touch-icon-precomposed.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="apple-mobile-web-app-title" content="Content">
<script>document.documentElement.className="js"</script>
<!--[if IE]><![endif]-->
<link href="/apple-touch-icon-precomposed.png" rel="image_src">
<link href="/apple-touch-icon-precomposed.png" rel="apple-touch-icon-precomposed">
<!--[if (lte IE 8)&(!IEMobile)]><link href="/packages/just/shapeshifter/css/all-old-ie.css" rel="stylesheet"><![endif]-->
<!--[if gte IE 9]><!--><link href="/packages/just/shapeshifter/css/main.css" rel="stylesheet"><!--<![endif]-->
</head>
<!--[if lte IE 8]>      <body class="login ie8">     <![endif]-->
<!--[if gte IE 9]><!--> <body class="login">     <!--<![endif]-->
<div class="login-header-area">
    <div class="page">
        <div class="content">
            <div class="content-body" style="background-color: transparent; border-width: 0; padding-bottom: 0; padding-top: 0;">
                <h1 class="login-header">Content</h1>
            </div>
        </div>
    </div>
</div>
<div class="login-area">
    <div class="page">
        <div class="content">
            <div class="content-body" style="background-color: transparent; border-width: 0; padding-bottom: 0; padding-top: 0;">
                {{ Form::open(array('class' => 'login-area-form')) }}
                    <fieldset class="section-start section-end">
                        <legend class="accessibility">{{__('login.signin')}}</legend>
                        @if ( ! Notification::all()->isEmpty())
                            {{ Notification::showAll() }}
                        @endif
                        <div class="separate">
                            {{ Form::label('email', __('login.email'), array('class' => 'accessibility')) }}
                            {{ Form::text('email', null, array('placeholder' => __('login.email'), 'autofocus')) }}
                        </div>
                        <div class="separate">
                            {{ Form::label('password', __('login.password'), array('class' => 'accessibility')) }}
                            {{ Form::password('password', array('placeholder' => __('login.password'))) }}
                        </div>
                        <button class="btn full" type="submit">{{__('login.signin')}}</button>
                    </fieldset>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>