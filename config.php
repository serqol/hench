<?php

const GMAIL_DRAFT_IDENTIFIER = '[Gmail]/Drafts';
const ORDER_ID = 'ЗАЯВКА';
const FROM_KEY = 98;
const DEFAULT_SUBJECT = 'Расчет и анализ ТРГ.';
const DEFAULT_CENTRE_EMAIL = 'uslugi.moskva@gmail.com';
const MAX_BROKEN_FIELDS_COUNT = 2;
const TEMPLATES_PATH = __DIR__ . '/templates/';
const TRG_CALCULATIONS_PATH = __DIR__ . '/report/trg_calculations/';

const MAPPERS = [
    'date'       => [
        'matchInfo' => ['Дата исследования', 'Дата проведения исследования'],
        'columnKey' => 'H',
        'fixMethod' => 'fixDate',
        'required'  => false,
    ],
    'name'       => [
        'matchInfo' => ['ФИО'],
        'columnKey' => 'B',
        'fixMethod' => 'fixFullName',
        'required'  => true,
    ],
    'birth_date' => [
        'matchInfo' => ['Дата рождения', 'Возраст пациента'],
        'columnKey' => 'D',
        'fixMethod' => 'fixDate',
        'required'  => true,
    ],
    'sex'        => [
        'matchInfo' => ['Пол пациента', 'Пол'],
        'columnKey' => 'E',
        'fixMethod' => 'fixSex',
        'required'  => true,
    ],
    'doctor'     => [
        'matchInfo' => ['врач'],
        'columnKey' => 'F',
        'fixMethod' => 'fixFullName',
        'required'  => false
    ],
    'city'       => [
        'matchInfo' => [],
        'columnKey' => 'G',
        'fixMethod' => null,
        'required'  => true,
    ],
    'ready_date' => [
        'matchInfo' => ['готов'],
        'columnKey' => 'K',
        'fixMethod' => 'fixDate',
        'required'  => true,
    ]
];

const FEEDBACK_NOT_REQUIRED = [              // TODO: Bad code, make something cleaner
    'picasso.ostrovskogo@gmail.com',
    'picasso.lomzhinskaya@gmail.com',
];

const CENTRES = [
    'picasso.ostrovskogo@gmail.com'       => 'Казань',
    'picasso.lomzhinskaya@gmail.com'      => 'Казань',
    'uslugi.moskva@gmail.com'             => 'Москва',
    'ak.yangelya.picasso@gmail.com'       => 'Москва Академика Янгеля',
    'picasso.altufyevo@gmail.com'         => 'Москва Алтуфьево',
    'arbat.picasso@gmail.com'             => 'Москва Арбат',
    'botanichesky.picasso@gmail.com'      => 'Москва Ботанический',
    'domodedovskaya.picasso@gmail.com'    => 'Москва Домодедовская',
    'picasso.kurkino@gmail.com'           => 'Москва Куркино',
    'ekt.picassodiagnostic@gmail.com'     => 'Екатеринбург',
    'picasso.turgeneva@gmail.com'         => 'Екатеринбург',
    'picasso.gorkogo@gmail.com'           => 'Нижний Новгород Максима Горького',
    'picasso.oskar@gmail.com'             => 'Краснодар Оскар',
    'bagrationovskaya.picasso@gmail.com'  => 'Москва Багратионовская',
    'picasso.kominterna@gmail.com'        => 'Нижний Новгород Коминтерна',
    'picasso.rostov@gmail.com'            => 'Ростов',
    'rstn.severny@gmail.com'              => 'Ростов',
    'samara.l.tolstogo@gmail.com'         => 'Самара Льва Толстого',
    'samara.k.marksa@gmail.com'           => 'Самара Карла Маркса',
    'krasnoyarsk.marksa@gmail.com'        => 'Красноярск',
    'lermontovskiy.picasso@gmail.com'     => 'Москва Лермонтовский',
    'lubercy.picasso@gmail.com'           => 'Москва Люберцы',
    'mitino.picasso@gmail.com'            => 'Москва Митино',
    'mytishi.picasso@gmail.com'           => 'Москва Мытищи',
    'paveleckaya.picasso@gmail.com'       => 'Москва Павелецкая',
    'picasso.nsk.krasny@gmail.com'        => 'Новосибирск Красный проспект',
    'nsk.vokzalnaya@gmail.com'            => 'Новосибирск Вокзальная',
    'nsk.gnesinykh@gmail.com'             => 'Новосибирск Гнесиных',
    'nsk.k.marksa@gmail.com'              => 'Новосибирск Карла Маркса',
    'perovodc@gmail.com'                  => 'Москва Перово',
    'pervomayskaya.picasso@gmail.com'     => 'Москва Первомайская',
    'picasso.ascona@gmail.com'            => 'Краснодар Аскона',
    'picasso.festivalny@gmail.com'        => 'Краснодар',
    'picasso.balaschiha@gmail.com'        => 'Москва Балашиха',
    'picasso.barricadnaya@gmail.com'      => 'Москва Баррикадная',
    'picasso.belorusskaya@gmail.com'      => 'Москва Белорусская',
    'picasso.bratislavskaya@gmail.com'    => 'Москва Братиславская',
    'picasso.dolgorukovskaya@gmail.com'   => 'Москва Новослободская',
    'picasso.dop3@gmail.com'              => 'Питер',
    'picasso.ibragimova@gmail.com'        => 'Казань',
    'picasso.kurskaya@gmail.com'          => 'Москва Курская',
    'picasso.kuzminki@gmail.com'          => 'Москва Кузьминки',
    'kaluzskaya.picasso@gmail.com'        => 'Москва Калужская',
    'picasso.krasnogorsk@gmail.com'       => 'Москва Красногорск',
    'picasso.krylatskoe@gmail.com'        => 'Москва Крылатское',
    'picasso.leninskiy@gmail.com'         => 'Москва Ленинский проспект',
    'odincovo.picasso@gmail.com'          => 'Москва Одинцово',
    'o.pole.picasso@gmail.com'            => 'Москва Октябрьское поле',
    'picasso.park.kultury@gmail.com'      => 'Москва Парк Культуры',
    'picasso.vernadskogo@gmail.com'       => 'Москва Проспект Вернадского',
    'podolsk.picasso@gmail.com'           => 'Москва Подольск',
    'preobrazhenkadc@gmail.com'           => 'Москва Преображенская',
    'proletarskaya.picasso@gmail.com'     => 'Москва Пролетарская',
    'pygevskogo@gmail.com'                => 'Москва Третьяковская',
    'picasso.rechnoy@gmail.com'           => 'Москва Речной Вокзал',
    'rigskaiy@gmail.com'                  => 'Москва Рижская',
    'seligerskaya.picasso@gmail.com'      => 'Москва Селигерская',
    'picasso.skobelevskaya@gmail.com'     => 'Москва Скобелевская',
    'sevastopolskaya.picasso@gmail.com'   => 'Москва Севастопольская',
    'aseretskayaelena@mail.ru'            => 'Москва Солнечный остров',
    'shodnenskayadc@gmail.com'            => 'Москва Сходненская',
    'picasso.taganskaya@gmail.com'        => 'Москва Таганская',
    'tepliy.stan.picasso@gmail.com'       => 'Москва Теплый стан',
    'timiryazevskaya01@gmail.com'         => 'Москва Тимирязевская',
    'picasso.troparevo@gmail.com'         => 'Москва Тропарево',
    'tulskaya.picasso@gmail.com'          => 'Москва Тульская',
    'voronez.moskovskiy@gmail.com'        => 'Воронеж',
    'picasso.engelsa@gmail.com'           => 'Воронеж',
    'kuznetskiy.most@gmail.com'           => 'Москва Кузнецкий мост',
    'picasso.alekseeva@gmail.com'         => 'Красноярск',
    'picasso.vedenyapina@gmail.com'       => 'Нижний Новгород',
    'picasso.vorovskogo@gmail.com'        => 'Сочи',
    'picasso.ufa.8marta@gmail.com'        => 'Уфа',
    'picasso.hasanatufana@gmail.com'      => 'Челны',
    'chelyabinsk.k.marksa@gmail.com'      => 'Челябинск',
];