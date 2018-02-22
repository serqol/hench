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
        preg_match('#\d{1,2}[/\.]{1}\d{1,2}[/\.]{1}\d{2,4}#', $date, $matches);
        if (!isset($matches[0])) {
            return $date;
        }
        return $matches[0];
    }

    public function fixSex($sex) {
        echo "Fixing sex {$sex} \n";
        if (strlen($sex) <= 1) {
            return $sex;
        }
        if (preg_match('#м#', $sex)) {
            $sex = 'м';
        } else {
            $sex = 'ж';
        }
        return $sex;
    }

    /**
     * @param $name
     * @return string
     */
    public function fixFullName($name) {
        $name = trim($name, " \t\n\r\0\x0B:;");
        echo "Fixing fullname {$name} \n";
        if ($this->isNameFixed($name)) {
            return $name;
        }
        $nameParts = explode(' ', $name);
        if (count($nameParts) !== 3) {
            return '';
        }
        $lastName = $nameParts[0];
        $firstNameInitial = substr($nameParts[1], 0, 2);
        $patronymicInitial = substr($nameParts[2], 0, 2);
        return "{$lastName} {$firstNameInitial}.{$patronymicInitial}.";
    }

    /**
     * @param $name
     * @return bool
     */
    public function isNameFixed($name) {
        return preg_match('#[А-Яа-я\-]+\s+[А-Яа-я\-]+\.\s*[А-Яа-я\-]+\.#', $name) > 0;
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