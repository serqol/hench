<?php

namespace Core\Services;

require_once __DIR__ . '/../../config.php';

use Core\Traits;

class Utils {

    use Traits\Initializer;

    /**
     * @var Matcher
     */
    private $_matcher;

    private function _init() {
        $this->_matcher = Matcher::initWithArgs();
    }

    /**
     * @param string $text
     * @param string $delimiter
     * @param bool $isSubRoutine
     * @return array
     */
    public function getListRowsAndRedirectionEmailFromText($text, $delimiter = "\n", $isSubRoutine = false) {
        $result = [];
        $mail = '';
        $index = 1;
        $string = strtok($text, $delimiter);
        while (is_string($string)) {
            if (!$isSubRoutine) {
                if (strpos($string, 'От кого:') === 0 || strpos($string, 'От:') === 0) { // TODO: Its ugly and fast. Make it clean and fast.
                    $mail = $this->_matcher->matchEmail($string);
                } elseif ($mail === '') {
                    $string = strtok($delimiter);
                    continue;
                }
            }
            if ($mail === '' && $isSubRoutine === false) {
                return $this->getListRowsAndRedirectionEmailFromText($text, $delimiter = "\n", true);
            }
            $string = trim($string);
            if ($this->isStringMatchesIndex(trim($string), $index)) {   //TODO: Use objects instead of arrays
                $result[] = trim($string);
                $index++;
            }
            $string = strtok($delimiter);
        }
        $result[FROM_KEY] = $mail;
        return $result;
    }

    /**
     * @param string $string
     * @param $index
     * @return mixed
     */
    public function isStringMatchesIndex($string, $index) {
        return strpos($string, (string)$index) === 0;
    }
}