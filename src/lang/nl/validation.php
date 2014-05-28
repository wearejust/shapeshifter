<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */


    "accepted"       => "Nee, :attribute moet geaccepteerd zijn.",
    "active_url"     => "Nee, :attribute is geen geldige URL.",
    "after"          => "Nee, :attribute moet een datum na :date zijn.",
    "alpha"          => "Nee, :attribute mag alleen letters bevatten.",
    "alpha_dash"     => "Nee, :attribute mag alleen letters, nummers, onderstreep(_) en strepen(-) bevatten.",
    "alpha_num"      => "Nee, :attribute mag alleen letters en nummers bevatten.",
    "array"          => "Nee, :attribute moet geselecteerde elementen bevatten.",
    "before"         => "Nee, :attribute moet een datum voor :date zijn.",
    "between"        => array(
        "numeric" => "Nee, :attribute moet tussen :min en :max zijn.",
        "file"    => "Nee, :attribute moet tussen :min en :max kilobytes zijn.",
        "string"  => "Nee, :attribute moet tussen :min en :max karakters zijn.",
        "array"   => "Nee, :attribute moet tussen :min en :max items bevatten."
    ),
    "confirmed"      => "Nee, :attribute bevestiging komt niet overeen.",
    "count"          => "Nee, :attribute moet precies :count geselecteerde elementen bevatten.",
    "countbetween"   => "Nee, :attribute moet tussen :min en :max geselecteerde elementen bevatten.",
    "countmax"       => "Nee, :attribute moet minder dan :max geselecteerde elementen bevatten.",
    "countmin"       => "Nee, :attribute moet minimaal :min geselecteerde elementen bevatten.",
    "date_format"    => "Nee, :attribute moet een geldig datum formaat bevatten.",
    "different"      => "Nee, :attribute en :other moeten verschillend zijn.",
    "email"          => "Nee, :attribute is geen geldig e-mailadres.",
    "exists"         => "Nee, :attribute bestaat niet.",
    "image"          => "Nee, :attribute moet een afbeelding zijn.",
    "in"             => "Nee, :attribute is ongeldig.",
    "integer"        => "Nee, :attribute moet een getal zijn.",
    "ip"             => "Nee, :attribute moet een geldig IP-adres zijn.",
    "match"          => "Nee, het formaat van :attribute is ongeldig.",
    "max"            => array(
        "numeric" => "Nee, :attribute moet minder dan :max zijn.",
        "file"    => "Nee, :attribute moet minder dan :max kilobytes zijn.",
        "string"  => "Nee, :attribute moet minder dan :max karakters zijn.",
        "array"   => "Nee, :attribute mag maximaal :max items bevatten."
    ),
    "mimes"          => "Nee, :attribute moet een bestand zijn van het bestandstype :values.",
    "min"            => array(
        "numeric" => "Nee, :attribute moet minimaal :min zijn.",
        "file"    => "Nee, :attribute moet minimaal :min kilobytes zijn.",
        "string"  => "Nee, :attribute moet minimaal :min karakters zijn.",
        "array"   => "Nee, :attribute moet minimaal :min items bevatten."
    ),
    "not_in"         => "Nee, het formaat van :attribute is ongeldig.",
    "numeric"        => "Nee, :attribute moet een nummer zijn.",
    "required"       => "Nee, :attribute is verplicht.",
    "required_with"  => "Nee, :attribute is verplicht i.c.m. :field",
    "same"           => "Nee, :attribute en :other moeten overeenkomen.",
    "size"           => array(
        "numeric" => "Nee, :attribute moet :size zijn.",
        "file"    => "Nee, :attribute moet :size kilobyte zijn.",
        "string"  => "Nee, :attribute moet :size characters zijn.",
        "array"   => "Nee, :attribute moet :size items bevatten."
    ),
    "unique"         => "Nee, :attribute is al in gebruik.",
    "url"            => "Nee, :attribute is geen geldige URL.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => array(),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => array_map('ucfirst', Lang::get('shapeshifter::attributes')),
);
