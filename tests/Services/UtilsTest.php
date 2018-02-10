<?php

namespace Tests\Services;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config.php';

use PHPUnit\Framework\TestCase;
use Core\Services\Utils;

class UtilsTest extends TestCase {

    /**
     * @var Utils
     */
    private $_utils;

    protected function setUp()
    {
        parent::setUp();
        $this->_utils = Utils::initWithArgs();
    }

    /**
     * @param string $input
     * @param array $expected
     * @dataProvider getRowsDataProvider
     */
    public function testGetRows($input, $expected) {
        $this->assertEquals(array_values($expected), array_values($this->_utils->getListRowsAndRedirectionEmailFromText($input)));
    }

    /**
     * @param $string
     * @param $index
     * @param $expected
     * @dataProvider isStringMatchesIndexDataProvider
     */
    public function testIsStringMatchesIndex($string, $index, $expected) {
        $this->assertEquals($expected, $this->_utils->isStringMatchesIndex($string, $index));
    }

    public function isStringMatchesIndexDataProvider() {
        return [
            ['1Анализ и расчет ТРГ по Dolphin:', 1, true],
            ['3 Оборудование на котором было сделано:Vatech', 3, true],
            ['2Дата исследования 07.12.2017', 3, false]
        ];
    }

    /**
     * @return array
     */
    public function getRowsDataProvider() {
        return [
            [
                'От кого: Доп Услуги Пикассо <picasso.dop2@gmail.com>
                Дата: 8 декабря 2017 г., 10:43
                Тема: Fwd: Спб /Анализ и расчет ТРГ по Dolphin \Богданова Светлана Юрьевна\
                \СД 32021 \ 12.12.2017
                Кому: Услуги Москва Пикассо <uslugi.moskva@gmail.com>
                
                
                
                
            
                1Анализ и расчет ТРГ по Dolphin:
                
                2Дата исследования 07.12.2017
                
                3 Оборудование на котором было сделано:Vatech
                
                4 ФИО полностью:Богданова Светлана Юрьевна
                
                5 Пол: Ж
                
                6 Дата рождения/Возраст:27.02.1966
                
                7 Телефоны: моб/дом/раб телефон его и близкого родственника ,ФИО
                (муж,сын,мама и т.д)
                
                8 Адрес электронной почты пациента:-
                
                9 ФИО лечащего врача:
                
                10 Телефон врача/Телефон клиники где проходит лечение:
                
                11 Адрес электронной почты врача: -
                
                12 Комментарий врача(из направления) или со слов пациента:направл нет
                
                13 Дата готовности с конкретизацией времени:.12.12.2017
                
                14 ФИО лаборанта,который отправил заявку/ Название центра: Манджикова Э.С \
                Старая Деревня
                
                
       
                -- 
                Спасибо, что воспользовались услугами независимой диагностики "Пикассо", по
                всем вопросам обращайтесь по телефону единой справочной:
                
                Диагностика "Пикассо" - гарантия качества и высокий уровень сервиса.
                Мы рады быть полезными Вам и Вашим пациентам.',
                [
                    '1Анализ и расчет ТРГ по Dolphin:',
                    '2Дата исследования 07.12.2017',
                    '3 Оборудование на котором было сделано:Vatech',
                    '4 ФИО полностью:Богданова Светлана Юрьевна',
                    '5 Пол: Ж',
                    '6 Дата рождения/Возраст:27.02.1966',
                    '7 Телефоны: моб/дом/раб телефон его и близкого родственника ,ФИО',
                    '8 Адрес электронной почты пациента:-',
                    '9 ФИО лечащего врача:',
                    '10 Телефон врача/Телефон клиники где проходит лечение:',
                    '11 Адрес электронной почты врача: -',
                    '12 Комментарий врача(из направления) или со слов пациента:направл нет',
                    '13 Дата готовности с конкретизацией времени:.12.12.2017',
                    '14 ФИО лаборанта,который отправил заявку/ Название центра: Манджикова Э.С \\',
                    'picasso.dop2@gmail.com',
                ]
            ]
        ];
    }
}