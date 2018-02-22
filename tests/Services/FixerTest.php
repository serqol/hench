<?php

namespace Tests\Services;

require_once '../../vendor/autoload.php';
require_once __DIR__ . '/../../config.php';

use PHPUnit\Framework\TestCase;
use Core\Services\Fixer;

class FixerTest extends TestCase {

    /**
     * @var Fixer
     */
    private $_fixer;

    protected function setUp() {
        $this->_fixer = Fixer::initWithArgs();
    }

    /**
     * @param string $date
     * @param string $expected
     * @dataProvider fixDateDataProvider
     */
    public function testFixDate($date, $expected) {
        $this->assertEquals($expected, $this->_fixer->fixDate($date));
    }

    public function fixDateDataProvider() {
        return [
            ['11.20.2017\4 года', '11.20.2017'],
            ['07.12.2017 13 лет', '07.12.2017'],
            ['03.06.1964г/53',    '03.06.1964'],
            ['15.06.11 (6 лет)',  '15.06.2011']
        ];
    }

    /**
     * @param string $name
     * @param string $expected
     * @dataProvider fixFullNameDataProvider
     */
    public function testFixFullName($name, $expected) {
        $this->assertEquals($expected, $this->_fixer->fixFullName($name));
    }

    /**
     * @param string $name
     * @param bool $expected
     * @dataProvider isFixedNameDataProvider
     */
    public function testIsFixedName($name, $expected) {
        $this->assertEquals($expected, $this->_fixer->isNameFixed($name));
    }

    public function isFixedNameDataProvider() {
        return [
            ['Логинов А.А.',      true],
            ['Дягтерёв П. В.',    true],
            ['Дягтерёв    в. В.', true],
            ['Дягтерёв П. В.',    true],
            ['Дягтерёв П. В.',    true],
        ];
    }

    /**
     * @return array
     */
    public function fixFullNameDataProvider() {
        return [
            ['Логинов Алексей Алексеевич', 'Логинов А.А.'],
            ['Петров Николай Николаевич',  'Петров Н.Н.'],
        ];
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider fixFieldsDataProvider
     */
    public function testFixFieldsByMappers($data, $expected) {
        $this->assertEquals($expected, $this->_fixer->fixFieldsByMappers($data, MAPPERS));
    }

    /**
     * @return array
     */
    public function fixFieldsDataProvider() {
        return [
            [
                [
                    'name'       => 'Aleshka',
                    'birth_date' => '11.20.2017\4 года'
                ],
                [
                    'name'       => 'Aleshka',
                    'birth_date' => '11.20.2017'
                ],
            ],
        ];
    }
}