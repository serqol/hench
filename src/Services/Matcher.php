<?php

namespace Core\Services;

use Core\Traits;

class Matcher {
    use Traits\Initializer;

    /**
     * @param array $queue
     * @param array $mappers
     * @return array
     */
    public function matchRowsByMappers($queue, $mappers) { // TODO: This method is fucking atrocious
        $result = [];
        foreach ($mappers as $mapperName => $mapperValues) {
            $isMapperSet = false;
            foreach ($mapperValues['matchInfo'] as $matchInfo) {
                foreach($queue as $itemKey => $itemValue) {
                    if (preg_match("~{$matchInfo}~", strtok($itemValue, ':')) === 1) {
                        $result[$mapperName] = trim(strtok(':'));
                        unset($queue[$itemKey]);
                        $isMapperSet = true;
                        break;
                    }
                }
                if ($isMapperSet) {
                    break;
                }
            }
        }
        return $result;
    }

    public function matchRowsByMappersNew($queue, $mappers) {

    }

    /**
     * @param string $text
     * @return string
     */
    public function matchEmail($text) {
        $result = null;
        $text = preg_split('~[\s]+~', $text);
        while (!empty($text)) {
            preg_match($this->_getEmailRegExp(), trim(array_shift($text), '<>'), $matches);
            if (isset($matches[0]) && is_string($matches[0])) {
                $result = $matches[0];
                break;
            }
        }
        return $result;
    }

    private function _getEmailRegExp() {
        return '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
    }
}

