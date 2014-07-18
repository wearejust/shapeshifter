<fieldset class="section section-sub">
    <legend class="accent"><span class="" style="display: block; margin-bottom: 1.375rem;">{{ $section->getName() }}</span></legend>
    @include('shapeshifter::attribute', array('attributes' => $section->getAttributes()))
</fieldset>
