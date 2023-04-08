<?php
namespace Drupal\mailingList\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class ReportController extends ControllerBase {

    protected function load() {
        $select = Database::getConnection()->select('mailinglist', 'm');
        $select->addField('m','mail');
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $entries;
    }


    public function report() {
        $content = array();
        $content['message'] = array(
            '#markup' => $this->t('Below is a list of all users who subscribed for news letter.'),
        );
        $headers = array(
            t('mail')
        );
        $rows = array();

        foreach($entries = $this->load() as $entry) {
            //Sanatize each entry
            $rows[] = $entry;
        }
        $content['table'] = array(
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
            '#empty' => t('No entries available'),
        );
        //Don't cache this page
        $content['#cache']['max-age'] = 0;
        return $content;
    }
}