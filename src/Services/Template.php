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

    use Initializer;

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
        return copy($templateFile, TRG_CALCULATIONS_PATH . $mailData['name'] . '. Заключение.');
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