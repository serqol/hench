<?php

namespace Core\Services;

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
     * @return array
     */
    public function getListRowsAndRedirectionEmailFromText($text, $delimiter = "\n") {
        $result = [];
        $index = 1;
        $string = strtok($text, $delimiter);
        while (is_string($string)) {
            $string = trim($string);
            if ($this->isStringMatchesIndex(trim($string), $index)) {   //TODO: Use objects instead of arrays
                $result[] = trim($string);
                $index++;
            } elseif (strpos($string, 'От кого:') === 0 || strpos($string, 'От:') === 0) {
                $result[FROM_KEY] = $this->_matcher->matchEmail($string);
            }
            $string = strtok($delimiter);
        }
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