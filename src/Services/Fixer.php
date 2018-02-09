<?php

namespace Core\Services;

use Core\Traits;

class Fixer {
    use Traits\Initializer;

    /**
     * @param $date
     * @return string
     */
    public function fixDate($date) {
        preg_match('#\d{1,2}[/\.]{1}\d{1,2}[/\.]{1}\d{4}#', $date, $matches);
        return isset($matches[0]) ? $matches[0] : $date;
    }

    /**
     * @param $name
     * @return string
     */
    public function fixFullName($name) {
        $nameParts = explode(' ', $name);
        if (count($nameParts) !== 3) {
            return $name;
        }
        $lastName = $nameParts[0];
        $firstNameInitial = substr($nameParts[1], 0, 2);
        $patronymicInitial = substr($nameParts[2], 0, 2);
        return "{$lastName} {$firstNameInitial}.{$patronymicInitial}.";
    }

    /**
     * @param array $fields
     * @param array $mappers
     * @return array
     */
    public function fixFieldsByMappers(array $fields, array $mappers) {
        $result = $fields;
        foreach ($fields as $fieldName => $fieldValue) {
            if (array_key_exists($fieldName, $mappers) && method_exists($this, $methodName = $mappers[$fieldName]['fixMethod'])) {
                $result[$fieldName] = $this->$methodName($fieldValue);
            } else {
                $result[$fieldName] = $fieldValue;
            }
        }
        return $result;
    }
}