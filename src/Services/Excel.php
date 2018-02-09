<?php

namespace Core\Services;

require_once __DIR__ . '/../../config.php';

use Core\Entity\Mail;
use Core\Traits;
use PhpOffice\PhpSpreadsheet\Reader\Ods;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Excel { //TODO: Get rid of all this crap and use this https://github.com/google/google-api-php-client to access gmail

    use Traits\Initializer;

    /**
     * @var Ods
     */
    private $_odsReader;

    /**
     * @var Fixer
     */
    private $_fixer;

    private function _init() {
        $this->_odsReader = new Ods();
        $this->_fixer = Fixer::initWithArgs();
    }

    /**
     * @param Spreadsheet $sheet
     * @return int
     * @throws \Throwable
     */
    public function newGetCurrentId($sheet) {
        $worksheet = $sheet->getActiveSheet();    //TODO: Implement properly when old id retrieval logic becomes obsolete
        $worksheet->getHighestRowAndColumn();
        return 0;
    }

    /**
     * @param Worksheet $sheet
     * @param int $row
     * @throws \Throwable
     * @return int
     */
    private function _getIdByRow($sheet, $row) {
        return (int)$sheet->getCell("A{$row}")->getValue() ?: 0;
    }

    /**
     * @param Mail[] $mails
     * @param string $reportDate
     * @param int $currentId
     * @return bool
     */
    public function saveMailCollectionToExcelByMappers(array $mails, $reportDate, $currentId = null) {
        $mappers = MAPPERS;
        $result = false;
        if (count($mails) < 0) {
            echo "No emails to save provided \n";
            return $result;
        }
        $basePath = __DIR__ . '/../../report/';
        $totalName = 'total.ods';
        try {
            $totalReport = file_exists($totalPath = $basePath.$totalName) ? $totalPath : $basePath . 'backup/' . $totalName;
            $spreadsheet = $this->_odsReader->load($totalReport);
            $activeSheet = $spreadsheet->getActiveSheet();
            $highestRow  = $activeSheet->getHighestRow();
            $currentId   = $currentId ?: $this->_getIdByRow($activeSheet, $highestRow) + 1;
            $currentRow  = $highestRow + 1;
            foreach ($mails as $mail) {
                $activeSheet->setCellValue("A{$currentRow}", $currentId);
                foreach ($mail->getData() as $columnKey => $columnValue) {
                    if ($columnKey === 'name') {
                        $columnValue = $this->_fixer->fixFullName($columnValue);
                    }
                    $activeSheet->setCellValue("{$mappers[$columnKey]['columnKey']}{$currentRow}", $columnValue);
                }
                $currentRow++;
                $currentId++;
            }
            $writer = IOFactory::createWriter($spreadsheet, 'Ods');
            $writer->save($totalPath);
            if (!file_exists($backupFile = $basePath . "backup/{$reportDate}.ods")) {
                $writer->save($backupFile);
            }
            $result = true;
        } catch (\Throwable $t) {
            echo $t->getMessage();
        }
        return $result;
    }
}
