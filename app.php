<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Core\Services\Imap;
use Core\Services\Utils;
use Core\Services\Matcher;
use Core\Entity\Mail;
use Core\Services\Fixer;
use Core\Services\Excel;
use Core\Services\Template;

$username  = isset($argv[1]) ? $argv[1] : null;
$password  = isset($argv[2]) ? $argv[2] : null;
$date      = isset($argv[3]) ? $argv[3] : null;
$currentId = isset($argv[4]) ? $argv[4] : null;

if ($username === null || $password === null) {
    die("Incorrect usage \n");
}
// INBOX
/** @var Imap $imap */
$imap = Imap::initWithArgs(['{imap.gmail.com:993/imap/ssl}', 'INBOX', GMAIL_DRAFT_IDENTIFIER, $username, $password]);
/** @var Utils $utils */
$utils = Utils::initWithArgs();
/** @var Matcher $matcher */
$matcher = Matcher::initWithArgs();
/** @var Fixer $fixer */
$fixer = Fixer::initWithArgs();
/** @var Excel $excel */
$excel = Excel::initWithArgs();
/** @var Template $template */
$template = Template::initWithArgs();

/**
 * @return Mail[]
 */
$extractMailObjectsFromInbox = function() use ($imap, $utils, $fixer, $matcher, $date) {
    $result = [];
    $messageIds = $imap->getMessageIdsBySubjectAndDate(ORDER_ID, $date);

    if (empty($messageIds)) {
        echo "No messages found on supplied date: {$date} \n";
        return $result;
    }

    foreach ($messageIds as $messageId) {
        $text = $imap->fetchBodyByMessageId($messageId);
        $rows = $utils->getListRowsAndRedirectionEmailFromText($text);
        $from = array_key_exists(FROM_KEY, $rows) ? $rows[FROM_KEY] : null;
        $mailData = $fixer->fixFieldsByMappers($matcher->matchRowsByMappers($rows, MAPPERS), MAPPERS);
        if (!empty($from)) {
            $mailData['city'] = array_key_exists($from, CENTRES) ? CENTRES[$from] : $from;
        } else {
            $mailData['city'] = 'Москва';
        }
        $mail = new Mail($messageId, $from, $mailData);
        $result[] = $mail;
    }
    return $result;
};

$reportDate = (new \DateTime($date))->format('j-F-Y');
$mailCollection = $extractMailObjectsFromInbox();
$excel->saveMailCollectionToExcelByMappers($mailCollection, $reportDate, $currentId);

foreach ($mailCollection as $mail) {
    $patientName = array_key_exists('name', $mail->getData()) ? $mail->getData()['name'] : 'unknown';
    $receivers = [DEFAULT_CENTRE_EMAIL];
    if (!in_array($mail->getFrom(), FEEDBACK_NOT_REQUIRED)) {
        $receivers[] = $mail->getFrom();
    }
    $imap->appendToDraft($receivers, "{$patientName}. " . DEFAULT_SUBJECT);
    if ($mail->isBrokenFields()) {
        $imap->markMessageAsUnreadById($mail->getMessageId());
    }
    $imap->saveAttachmentsByMessageId($mail->getMessageId(), $fixer->fixFullName($patientName));
    $template->generateTemplateByMail($mail);
}
