<?php

namespace Core\Services;

use Core\Traits;

class Imap {

    use Traits\Initializer;

    static protected $_hostRef;

    static protected $_stream;

    static protected $_draftStream;

    static protected $_mainBox;

    static protected $_draftBox;

    /**
     * @param string $hostRef
     * @param string $mainBox
     * @param string $draftBox
     * @param string $username
     * @param string $password
     * @throws \Exception
     */
    private function _init($hostRef, $mainBox, $draftBox, $username, $password) {
        self::$_hostRef     = $hostRef;
        self::$_mainBox     = $mainBox;
        self::$_draftBox    = $draftBox;
        self::$_stream      = imap_open($hostRef.$mainBox, $username, $password);
        self::$_draftStream = imap_open($hostRef.$draftBox, $username, $password);
    }

    public function listMailboxes() {
        return imap_list(self::$_stream, self::$_hostRef, '*');
    }

    /**
     * @param array $receivers
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function appendToDraft(array $receivers, $subject = 'draft', $message = '') {
        $receivers = implode(',', $receivers);
        try {
            return imap_append(self::$_draftStream, self::$_hostRef.self::$_draftBox,
                "To: {$receivers}\r\n"
                . "Subject: {$subject}\r\n"
                . "\r\n"
                . "{$message}\r\n"
            );
        } catch (\Throwable $t) {
            echo "{$t->getMessage()}\n";
        }
    }

    /**
     * @param int $messageId
     * @param string $fileName
     */
    public function saveAttachmentsByMessageId($messageId, $fileName) {
        $structure = imap_fetchstructure(self::$_stream, $messageId);

        $attachments = array();

        if(isset($structure->parts) && count($structure->parts)) {
            for($i = 0; $i < count($structure->parts); $i++) {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );

                if($structure->parts[$i]->ifdparameters) {
                    foreach($structure->parts[$i]->dparameters as $object) {
                        if(strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                if($structure->parts[$i]->ifparameters) {
                    foreach($structure->parts[$i]->parameters as $object) {
                        if(strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                if($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody(self::$_stream, $messageId, $i+1);

                    if($structure->parts[$i]->encoding == 3) {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    elseif($structure->parts[$i]->encoding == 4) {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }

        $fileIndex = 1;
        foreach($attachments as $attachment) {
            $name = array_key_exists('name', $attachment) ? $attachment['name'] : $attachment['filename'];
            $nameParts = explode('.', $name);
            if($attachment['is_attachment'] == 1 && isset($nameParts[1]) && in_array($nameParts[1], ['jpg', 'png'])) {
                $fileNameNumerated = $fileName . ' - ' . (string)$fileIndex;
                $fp = fopen(__DIR__ . '/../../report/trg_pictures/' . $fileNameNumerated, "w+");
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
                $fileIndex++;
            }
        }
    }

    /**
     * @param string $messageId
     * @return object
     */
    public function getHeaderByMessageId($messageId) {
        return imap_header(self::$_stream, $messageId);
    }

    /**
     * @param string $subject
     * @param string $date
     * @return array
     */
    public function getMessageIdsBySubjectAndDate($subject, $date = 'now') {
        $criteria = 'SUBJECT ' . '"' . $subject . '"';
        $date = (new \DateTime($date))->format('j-F-Y');
        $criteria .= ' ON ' . '"' . $date . '"';
        return imap_search(self::$_stream, $criteria);
    }

    /**
     * @param int $messageId
     */
    public function markMessageAsUnreadById($messageId) {
        imap_clearflag_full(self::$_stream, $messageId, "\\Seen");
    }

    /**
     * @param int $messageId
     * @return string
     */
    public function fetchBodyByMessageId($messageId) {
        $text = imap_fetchbody(self::$_stream, $messageId, '1.1');
        return base64_decode($text);
    }

    public function check($resource) {
        return imap_check($resource);
    }
}
