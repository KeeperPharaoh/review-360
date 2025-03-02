<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Сообщения валидации
    |--------------------------------------------------------------------------
    |
    | Следующие строки содержат сообщения об ошибках по умолчанию, используемые
    | валидатором. Некоторые из этих правил имеют несколько версий, такие как
    | правила размера. Не стесняйтесь изменять эти сообщения.
    |
    */

    'accepted' => ':attribute должен быть принят.',
    'accepted_if' => ':attribute должен быть принят, если :other равен :value.',
    'active_url' => ':attribute не является действительным URL.',
    'after' => ':attribute должен быть датой после :date.',
    'after_or_equal' => ':attribute должен быть датой, не ранее :date.',
    'alpha' => ':attribute может содержать только буквы.',
    'alpha_dash' => ':attribute может содержать только буквы, цифры, дефисы и подчеркивания.',
    'alpha_num' => ':attribute может содержать только буквы и цифры.',
    'array' => ':attribute должен быть массивом.',
    'before' => ':attribute должен быть датой до :date.',
    'before_or_equal' => ':attribute должен быть датой, не позднее :date.',
    'between' => [
        'array' => ':attribute должен содержать от :min до :max элементов.',
        'file' => ':attribute должен быть от :min до :max килобайт.',
        'numeric' => ':attribute должен быть между :min и :max.',
        'string' => ':attribute должен содержать от :min до :max символов.',
    ],
    'boolean' => 'Поле :attribute должно быть истинным или ложным.',
    'confirmed' => 'Подтверждение :attribute не совпадает.',
    'current_password' => 'Пароль неверен.',
    'date' => ':attribute не является корректной датой.',
    'date_equals' => ':attribute должен быть датой, равной :date.',
    'date_format' => ':attribute не соответствует формату :format.',
    'declined' => ':attribute должен быть отклонен.',
    'declined_if' => ':attribute должен быть отклонен, если :other равно :value.',
    'different' => ':attribute и :other должны отличаться.',
    'digits' => ':attribute должен содержать :digits цифр.',
    'digits_between' => ':attribute должен быть от :min до :max цифр.',
    'dimensions' => ':attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Поле :attribute содержит повторяющееся значение.',
    'email' => ':attribute должен быть действительным адресом электронной почты.',
    'ends_with' => ':attribute должен заканчиваться одним из следующих значений: :values.',
    'enum' => 'Выбранное значение для :attribute недопустимо.',
    'exists' => 'Выбранное значение для :attribute недопустимо.',
    'file' => ':attribute должен быть файлом.',
    'filled' => 'Поле :attribute обязательно для заполнения.',
    'gt' => [
        'array' => ':attribute должен содержать больше :value элементов.',
        'file' => ':attribute должен быть больше :value килобайт.',
        'numeric' => ':attribute должен быть больше :value.',
        'string' => ':attribute должен быть больше :value символов.',
    ],
    'gte' => [
        'array' => ':attribute должен содержать :value элементов или больше.',
        'file' => ':attribute должен быть не менее :value килобайт.',
        'numeric' => ':attribute должен быть не менее :value.',
        'string' => ':attribute должен быть не менее :value символов.',
    ],
    'image' => ':attribute должен быть изображением.',
    'in' => 'Выбранное значение для :attribute недопустимо.',
    'in_array' => 'Поле :attribute не существует в :other.',
    'integer' => ':attribute должен быть целым числом.',
    'ip' => ':attribute должен быть действительным IP-адресом.',
    'ipv4' => ':attribute должен быть действительным IPv4-адресом.',
    'ipv6' => ':attribute должен быть действительным IPv6-адресом.',
    'json' => ':attribute должен быть корректной строкой JSON.',
    'lt' => [
        'array' => ':attribute должен содержать меньше :value элементов.',
        'file' => ':attribute должен быть меньше :value килобайт.',
        'numeric' => ':attribute должен быть меньше :value.',
        'string' => ':attribute должен быть меньше :value символов.',
    ],
    'lte' => [
        'array' => ':attribute не должен содержать более :value элементов.',
        'file' => ':attribute должен быть не более :value килобайт.',
        'numeric' => ':attribute должен быть не более :value.',
        'string' => ':attribute должен быть не более :value символов.',
    ],
    'mac_address' => ':attribute должен быть допустимым MAC-адресом.',
    'max' => [
        'array' => ':attribute не должен содержать более :max элементов.',
        'file' => ':attribute не должен быть больше :max килобайт.',
        'numeric' => ':attribute не должен быть больше :max.',
        'string' => ':attribute не должен быть больше :max символов.',
    ],
    'mimes' => ':attribute должен быть файлом одного из следующих типов: :values.',
    'mimetypes' => ':attribute должен быть файлом одного из следующих типов: :values.',
    'min' => [
        'array' => ':attribute должен содержать как минимум :min элементов.',
        'file' => ':attribute должен быть не менее :min килобайт.',
        'numeric' => ':attribute должен быть не менее :min.',
        'string' => ':attribute должен быть не менее :min символов.',
    ],
    'multiple_of' => ':attribute должен быть кратным :value.',
    'not_in' => 'Выбранное значение для :attribute недопустимо.',
    'not_regex' => 'Формат :attribute неверен.',
    'numeric' => ':attribute должен быть числом.',
    'present' => 'Поле :attribute должно быть присутствующим.',
    'prohibited' => 'Поле :attribute запрещено.',
    'prohibited_if' => 'Поле :attribute запрещено, если :other равно :value.',
    'prohibited_unless' => 'Поле :attribute запрещено, если :other не содержится в :values.',
    'prohibits' => 'Поле :attribute запрещает присутствие :other.',
    'regex' => 'Формат :attribute неверен.',
    'required' => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys' => 'Поле :attribute должно содержать записи для: :values.',
    'required_if' => 'Поле :attribute обязательно для заполнения, если :other равно :value.',
    'required_unless' => 'Поле :attribute обязательно для заполнения, если :other не содержится в :values.',
    'required_with' => 'Поле :attribute обязательно для заполнения, если присутствует :values.',
    'required_with_all' => 'Поле :attribute обязательно для заполнения, если присутствуют :values.',
    'required_without' => 'Поле :attribute обязательно для заполнения, если :values отсутствует.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, если отсутствуют все значения :values.',
    'same' => ':attribute и :other должны совпадать.',
    'size' => [
        'array' => ':attribute должен содержать :size элементов.',
        'file' => ':attribute должен быть размером :size килобайт.',
        'numeric' => ':attribute должен быть равным :size.',
        'string' => ':attribute должен быть равным :size символов.',
    ],
    'starts_with' => ':attribute должен начинаться с одного из следующих значений: :values.',
    'string' => ':attribute должен быть строкой.',
    'timezone' => ':attribute должен быть действительным часовым поясом.',
    'unique' => ':attribute уже занят.',
    'uploaded' => 'Загрузка :attribute не удалась.',
    'url' => ':attribute должен быть действительным URL.',
    'uuid' => ':attribute должен быть корректным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Свои сообщения настраиваемой валидации
    |--------------------------------------------------------------------------
    |
    | Здесь можно задать свои сообщения для атрибутов, используя формат
    | "attribute.rule". Это позволяет быстро задать конкретное сообщение
    | для заданного правила валидации.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'свое-сообщение',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Свои названия атрибутов
    |--------------------------------------------------------------------------
    |
    | Следующие строки используются для подстановки в сообщения удобочитаемых
    | названий полей, например "Адрес электронной почты" вместо "email".
    | Это помогает сделать сообщения более выразительными.
    |
    */

    'attributes' => [],

];
