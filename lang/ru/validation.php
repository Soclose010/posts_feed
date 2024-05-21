<?php

return [
    'confirmed' => 'Поле :attribute не совпадает с полем подтверждения.',
    'current_password' => 'Пароль неверный.',
    'date' => 'Поле :attribute должно быть датой.',
    'email' => 'Поле :attribute должно быть адресом электронной почты.',
    'max' => [
        'string' => 'Поле :attribute должно быть не более :max символов.',
    ],
    'max_digits' => 'The :attribute field must not have more than :max digits.',
    'min' => [
        'string' => 'Поле :attribute должно быть не менее :min символов.',
    ],
    'password' => [
        'mixed' => 'Поле :attribute должно содержать минимум одну букву верхнего и нижнего регистра.',
        'numbers' => 'Поле :attribute должно содержать хотя бы одну цифру.',
        'symbols' => 'Поле :attribute должно содержать хотя бы один символ.',
        'uncompromised' => 'Данный :attribute оказался в утечке данных. Пожалуйста, выберите другой :attribute.',
    ],
    'required' => 'Поле :attribute является обязательным.',
    'required_with' => 'Поле :attribute необходимо, если присутствует :values.',
    'string' => 'Поле :attribute должно быть строкой.',
    'unique' => 'Такой :attribute уже занят.',
    'same' => 'Поле :attribute должно совпадать с полем :other.',

    'attributes' => [
        "username" => "имя пользователя",
        "email" => "адрес электронной почты",
        "password" => "пароль",
        "title" => "заголовок",
        "body" => "текст",
        "date" => "дата"
    ],
];
