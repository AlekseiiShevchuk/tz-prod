<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Le :attribute doit être acceptée.',
    'active_url'           => 'Le :attribute n\'est pas une URL valide.',
    'after'                => 'Le :attribute doit être une date après :date.',
    'alpha'                => 'Le :attribute ne peut contenir que des lettres.',
    'alpha_dash'           => 'Le :attribute ne peut contenir que des lettres, des chiffres et des tirets',
    'alpha_num'            => 'Le :attribute ne peut contenir que des lettres et des chiffres.',
    'array'                => 'Le :attribute doit être un tableau.',
    'before'               => 'Le :attribute doit être une date avant :date.',
    'between'              => [
        'numeric' => 'Le :attribute doit être entre :min et :max.',
        'file'    => 'Le :attribute doit être entre :min et :max kilobytes.',
        'string'  => 'Le :attribute doit être entre :min et :max personnages.',
        'array'   => 'Le :attribute doit avoir entre :min et :max articles.',
    ],
    'boolean'              => 'Le :attribute champ doit être vrai ou faux.',
    'confirmed'            => 'Le :attribute la confirmation ne correspond pas.',
    'date'                 => 'Le :attribute n\'est pas une date valide.',
    'date_format'          => 'Le :attribute ne correspond pas au format :format.',
    'different'            => 'Le :attribute et :other doit être différente.',
    'digits'               => 'Le :attribute doit être :digits chiffres.',
    'digits_between'       => 'Le :attribute doit être entre :min et :max chiffres.',
    'dimensions'           => 'Le :attribute a des dimensions d\'image non valides.',
    'distinct'             => 'Le :attribute champ a une valeur dupliquée.',
    'email'                => 'Le :attribute doit être une adresse e-mail valide.',
    'exists'               => 'Le choisi :attribute est invalide.',
    'file'                 => 'Le :attribute doit être un fichier.',
    'filled'               => 'Le :attribute champ requis.',
    'image'                => 'Le :attribute doit être une image.',
    'in'                   => 'Le choisi :attribute est invalide.',
    'in_array'             => 'Le :attribute champ n\'existe pas dans :other.',
    'integer'              => 'Le :attribute doit être un entier.',
    'ip'                   => 'Le :attribute doit être une adresse IP valide.',
    'json'                 => 'Le :attribute doit être une chaîne JSON valide.',
    'max'                  => [
        'numeric' => 'Le :attribute ne peut être supérieur à :max.',
        'file'    => 'Le :attributene peut être supérieur à :max kilobytes.',
        'string'  => 'Le :attribute ne peut être supérieur à :max personnages.',
        'array'   => 'Le :attribute peut ne pas avoir plus de :max articles.',
    ],
    'mimes'                => 'Le :attribute doit être un fichier de type: :values.',
    'mimetypes'            => 'Le :attribute doit être un fichier de type: :values.',
    'min'                  => [
        'numeric' => 'Le :attribute doit être au moins :min.',
        'file'    => 'Le :attribute doit être au moins :min kilobytes.',
        'string'  => 'Le :attribute doit être au moins :min personnages.',
        'array'   => 'Le :attribute doit avoir au moins :min articles.',
        ],
    'not_in'               => 'Le choisi :attribute est invalide.',
    'numeric'              => 'Le :attribute doit être un nombre.',
    'present'              => 'Le :attribute champ doit être présent.',
    'regex'                => 'Le :attribute format n\'est pas valide.',
    'required'             => 'Le :attribute champ requis.',
    'required_if'          => 'Le :attribute champ est requis lorsque :other est :value.',
    'required_unless'      => 'Le :attribute champ est obligatoire sauf si :other est dans :values.',
    'required_with'        => 'Le :attribute champ est requis lorsque :values est présent.',
    'required_with_all'    => 'Le :attribute champ est requis lorsque :values est présent.',
    'required_without'     => 'Le :attribute champ est requis lorsque :values n\'est pas présent.',
    'required_without_all' => 'Le :attribute est obligatoire lorsque aucun :values sont présents.',
    'same'                 => 'Le :attribute et :other doit correspondre.',
    'size'                 => [
        'numeric' => 'Le :attribute doit être :size.',
        'file'    => 'Le :attribute doit être :size kilobytes.',
        'string'  => 'Le :attribute doit êtree :size personnages.',
        'array'   => 'Le :attribute doit contenir :size articles.',
    ],
    'string'               => 'Le :attribute doit être une chaîne.',
    'timezone'             => 'Le :attribute doit être une zone valide.',
    'unique'               => 'Le :attribute a déjà été pris.',
    'uploaded'             => 'Le :attribute impossible de télécharger.',
    'url'                  => 'Le :attribute le format n\'est pas valide.',

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

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'message-personnalisé',
        ],
    ],

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

    'attributes' => [],

];
