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

    'accepted'             => ':attribute должен быть принят.',
    'active_url'           => ':attribute - некорректный URL.',
    'after'                => ':attribute должен быть датой после :date.',
    'alpha'                => ':attribute должен содержать только буквы.',
    'alpha_dash'           => ':attribute должен содержать только буквы, числа и тире.',
    'alpha_num'            => ':attribute должен содержать только буквы и числа.',
    'array'                => ':attribute должен be an array.',
    'before'               => ':attribute должен be датой перед :date.',
    'between'              => [
        'numeric' => ':attribute должно быть между :min и :max.',
        'file'    => ':attribute должен иметь размер от :min до :max Кб.',
        'string'  => ':attribute должен иметь длину от :min до :max символов.',
        'array'   => ':attribute должен иметь от :min до :max элементов.',
    ],
    'boolean'              => ':attribute поле должно быть true или false.',
    'confirmed'            => ':attribute не совпадает с подтверждением.',
    'date'                 => ':attribute - некорректная дата.',
    'date_format'          => ':attribute не совпадает с форматом :format.',
    'different'            => ':attribute и :other должен различаться.',
    'digits'               => ':attribute должен состоять из :digits цифр.',
    'digits_between'       => ':attribute должен содержать от :min до :max цифр.',
    'dimensions'           => ':attribute имеет недопустимые размеры файла.',
    'distinct'             => ':attribute поле имеет дубликат.',
    'email'                => ':attribute должно быть корректным электронным адресом.',
    'exists'               => 'Выбранный :attribute не существует.',
    'file'                 => ':attribute должен быть файлом.',
    'filled'               => ':attribute - обязательное поле.',
    'image'                => ':attribute должно быть изображением.',
    'in'                   => 'Выбранный :attribute не корректен.',
    'in_array'             => ':attribute поле не входит в :other.',
    'integer'              => ':attribute должно быть числом.',
    'ip'                   => ':attribute должен быть корректным IP адресом.',
    'json'                 => ':attribute должно быть корректной JSON строкой.',
    'max'                  => [
        'numeric' => ':attribute может быть не больше, чем :max.',
        'file'    => ':attribute может быть не больше, чем :max Кб.',
        'string'  => ':attribute может содержать не больше, чем :max символов.',
        'array'   => ':attribute может содержать не больше, чем :max элементов.',
    ],
    'mimes'                => ':attribute должен быть файлом типа: :values.',
    'min'                  => [
        'numeric' => ':attribute должно быть не меньше, чем :min.',
        'file'    => ':attribute должно быть не меньше :min Кб.',
        'string'  => ':attribute должно содержать не меньше :min символов.',
        'array'   => ':attribute должно содержать не меньше :min элементов.',
    ],
    'not_in'               => 'Выбранный :attribute не корректен.',
    'numeric'              => ':attribute должно быть числом.',
    'present'              => ':attribute - обязательное поле.',
    'regex'                => ':attribute формат не корректен.',
    'required'             => ':attribute - обязательное поле.',
    'required_if'          => ':attribute - обязательное поле, если :other имеет значение :value.',
    'required_unless'      => ':attribute - обязательное поле, если :other не входит в :values.',
    'required_with'        => ':attribute - обязательное поле, если есть :values.',
    'required_with_all'    => ':attribute - обязательное поле, если есть :values.',
    'required_without'     => ':attribute - обязательное поле, если :values отсутствует.',
    'required_without_all' => ':attribute - обязательное поле, если отсутствуют все :values.',
    'same'                 => ':attribute и :other должны совпадать.',
    'size'                 => [
        'numeric' => ':attribute должно равняться :size.',
        'file'    => 'размер файла :attribute должен быть :size Кб.',
        'string'  => ':attribute должно иметь :size символов.',
        'array'   => ':attribute должно содержать :size элементов.',
    ],
    'string'               => ':attribute должно быть строкой.',
    'timezone'             => ':attribute должно быть корректной временной зоной.',
    'unique'               => ':attribute должен быть уникальным.',
    'url'                  => ':attribute имеет некорректный формат.',

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
            'rule-name' => 'custom-message',
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
