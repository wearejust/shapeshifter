<fieldset class="section section-sub">
    <legend class="wrap">
    	<span class="" style="display: block; font-weight: bold; margin-bottom: 1.375rem;">{{ $section->getName() }}</span>
    </legend>
    @include('shapeshifter::attribute', array('attributes' => $section->getAttributes()))
</fieldset>
