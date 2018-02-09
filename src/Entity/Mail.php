<?php

namespace Core\Entity;

require_once __DIR__ . '/../../config.php';

class Mail {

    /**
     * Mail constructor.
     * @param int $id
     * @param string $from
     * @param array $data
     */
    public function __construct($id, $from, $data) {
        $this->_messageId = $id;
        $this->_from      = $from;
        $this->_data      = $data;
    }

    /**
     * @var int
     */
    private $_messageId;

    /**
     * @var string
     */
    private $_from;

    /**
     * @var array
     */
    private $_data;

    public function getMessageId() {
        return $this->_messageId;
    }

    /**
     * @param int $id
     */
    public function setMessageId($id) {
        $this->_messageId = $id;
    }

    /**
     * @return string
     */
    public function getFrom() {
        return $this->_from;
    }

    /**
     * @param $from
     * @return $this
     */
    public function setFrom($from) {
        $this->_from = $from;
        return $this;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data) {
        $this->_data = $data;
        return $this;
    }

    public function isBrokenFields() {
        $brokenFields = 0;
        $requiredFields = array_keys(array_filter(MAPPERS, function ($mapper) {
            return $mapper['required'] == true;
        }));
        foreach ($requiredFields as $requiredField) {
            if (!array_key_exists($requiredField, $this->getData()) || !$this->_isValueValid($this->getData()[$requiredField])) {
                $brokenFields++;
            }
        }
        return $brokenFields > 0;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function _isValueValid($value) {
        return $value !== null && $value !== '';
    }
}