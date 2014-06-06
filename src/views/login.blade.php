@extends('shapeshifter::layouts.master')

@section('login')
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
@stop
