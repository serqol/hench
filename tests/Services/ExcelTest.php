<?php

namespace Tests\Services;

require_once __DIR__ . '/../../config.php';

use PHPUnit\Framework\TestCase;
use Core\Services\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Ods;

class ExcelTest extends TestCase {

    /**
     * @var Excel
     */
    private $_excelService;

    /**
     * @var Ods
     */
    private $_odsReader;

    protected function setUp() {
        $this->_odsReader    = new Ods();
        $this->_excelService = Excel::initWithArgs();
    }

    /**
     * @param string $input
     * @param string $expected
     * @dataProvider getLastIdFromSheetDataProvider
     * @throws \Throwable
     */
    public function testGetLastIdFromSheet($input, $expected) { //TODO: Complete test
        $spreadsheet = $this->_odsReader->load($input);
        //$this->assertEquals($expected, $this->_excelService->getCurrentId($spreadsheet));
    }

    private function getLastIdFromSheetDataProvider() {
        return __DIR__ . '/../Cases/ExcelTestCase.ods';
    }
}