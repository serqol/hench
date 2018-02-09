<?php

namespace Tests\Services;

require_once '../../vendor/autoload.php';
require_once __DIR__ . '/../../config.php';

use PHPUnit\Framework\TestCase;
use Core\Services\Matcher;

class MatcherTest extends TestCase {

    /**
     * @var Matcher
     */
    private $_matcher;

    protected function setUp()
    {
        parent::setUp();
        $this->_matcher = Matcher::initWithArgs();
    }

    /**
     * fio, birth date, sex, doctor, city, moscow requisites
     */

    /**
     * @param array $input
     * @param array $expected
     * @dataProvider matchQueueDataProvider
     */
    public function testMatchRowsByMappers($input, $expected) {
        $this->assertEquals($expected, $this->_matcher->matchRowsByMappers($input, MAPPERS));
    }

    /**
     * @param string $subject
     * @param string $expected
     */
/*    public function testMatchDateInSubject($subject, $expected) {
        $this->assertEquals($expected, $this->_matcher->matchDateInSubject($subject));
    }*/

    /**
     * @return array
     */
/*    public function matchDateInSubjectProvider() {
        return [
            ['Тема: Расчет и Анализ ТРГ/ Асатрян Л.А./ ДЦ Люберцы/ 29.12.17 до 18:00', ['date' => '29.12.17', 'time' => 'до 18:00']],
            ['Тема: ЗАЯВКА: МОСКВА/Расчет и Анализ ТРГ/ Борисов Игорь Борисович /Готовность 29.12.17 до 12:00', ['date' => '29.12.17', 'time' => 'до 12:00']],
            ['Тема: ЗАЯВКА: Новосибирск/Красный пр-т/Анализ и Расчет ТРГ/Жилина Александра Андреевна/25.12.17', ['date' => '25.12.17', 'time' => null]],
            ['Тема: ЗАЯВКА: Спб /Анализ и расчет ТРГ по Dolphin \Богданова Светлана Юрьевна\ \СД 32021 \ 12.12.2017', ['date' => '12.12.2017', 'time' => null]],
        ];
    }*/

    /**
     * @param string $input
     * @param string $expected
     * @dataProvider matchEmailDataProvider
     */
    public function testMatchEmail($input, $expected) {
        $this->assertEquals($expected, $this->_matcher->matchEmail($input));
    }

    public function matchEmailDataProvider() {
        return [
            ['ddasdas <serqol@mail.ru> dsadasd', 'serqol@mail.ru'],
            ['notanemail@bro dsa', false]
        ];
    }

    public function matchQueueDataProvider() {
        return [
            [
                [
                    '1.Номер исследования: 19319',
                    '2.Дата проведения исследования: 27.12.2017',
                    '3.ФИО пациента полностью: Борисов Игорь Борисович',
                    '4.Пол пациента: муж',
                    '5.Дата рождения/Возраст пациента: 03.06.1964г/53 года',
                    '6.Телефоны: мобильный/домашний/рабочий телефон его и близкого родственника',
                    '7.Адрес электронной почты пациента:  best77@list.ru',
                    '8.ФИО лечащего врача: Резина И.Б.',
                    '9.Телефон врача/Телефон клиники, где проходит лечение:нет',
                    '10.Адрес электронной почты врача: rezinamari@gmail.com',
                    '11.Комментарий врача (из направления) или со слов пациента: выслать на',
                    '12.ФИО лаборанта, который отправил заявку: Коваленко О.Б.',
                    '13.Сумма по чеку, которую заплатил пациент: 3700,00',
                    '14.Дата готовности с конкретизацией времени: 29.12.17 до 12:00',
                ],
                [
                    'name'       => 'Борисов Игорь Борисович',
                    'birth_date' => '03.06.1964г/53 года',
                    'sex'        => 'муж',
                    'doctor'     => 'Резина И.Б.',
                    'ready_date' => '29.12.17 до 12',
                    'id'         => '19319',
                    'date'       => '27.12.2017',
            //        'city'       => 'Moscow',
                ]
            ]

        ];
    }
}