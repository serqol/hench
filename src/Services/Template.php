<?php

namespace Core\Services;

require_once __DIR__ . '/../../config.php';

use Core\Entity\Mail;
use Core\Traits\Initializer;

class Template {

    /**
     * @var array
     */
    private $_templatesFiles;

    /**
     * @var Fixer
     */
    private $_fixer;

    /**
     * @var Utils
     */
    private $_utils;

    use Initializer;

    protected function _init() {
        $this->_fixer = Fixer::initWithArgs();
        $this->_utils = Utils::initWithArgs();
    }

    /**
     * @param Mail $mail
     * @return bool
     */
    public function generateTemplateByMail(Mail $mail) {
        if (!array_key_exists('city', $mailData = $mail->getData()) || !array_key_exists('name', $mailData)) {
            return false;
        }
        $city = explode(' ', $mailData['city'])[0];
        $templateFile = $this->getTemplateFilenameByName($city);
        if (!file_exists($templateFile)) {
            echo "File {$templateFile} does not exist! \n";
            return false;
        }
        $fileExtension = $this->_utils->getFileExtension($templateFile);
        return copy($templateFile, TRG_CALCULATIONS_PATH . $mailData['name'] . ' Заключение.' . $fileExtension);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getTemplateFilenameByName($name) {
        if (!isset($this->_templatesFiles)) {
            $this->_templatesFiles =  array_filter(scandir(TEMPLATES_PATH), function ($fileName) {
                return !in_array($fileName, ['.', '..']);
            });
        }

        foreach ($this->_templatesFiles as $key => $templateFile) {
            if (preg_match("~{$name}~", $templateFile)) {
                return TEMPLATES_PATH . $templateFile;
            };
         }
    }
}