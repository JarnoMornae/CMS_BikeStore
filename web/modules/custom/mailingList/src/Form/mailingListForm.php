<?php

namespace Drupal\mailinglist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class mailinglistForm extends FormBase {
    public function getFormId() {
        return 'mailinglist_email_form'; 
    }

    public function buildForm(array $form, FormStateInterface $form_state ) {
        /**in deze functie gaan we de opbouw van onze form doen. */
        //ksm($node);
        $form['email'] = array(
            '#title' => t("Email Address"),
            '#type' => 'textfield',
            '#size' => 25,
            '#description' => t("Vul het correcte email adress in om onze reclamefolder te ontvangen."),
            '#required' => TRUE
        );
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('indienen')
        );
        return $form;
    }

    /**
     * (@inheritdoc)
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $value = $form_state->getValue('email');
        if($value == !\Drupal::service('email.validator')->isValid($value)) {
            $form_state->setErrorByName('email', t('The email address %mail is not valid', array('%mail' => $value)));
         }
    }

    /**
     * (@inheritdoc)
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
       $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

       $fields = array(
           'mail' => $form_state->getValue('email'),
           'uid' => $user->id(),
           'created' => time()
       );

       \Drupal::database()
        ->insert('mailinglist')
        ->fields($fields)
        ->execute();

       \Drupal::messenger()->addMessage(t('You will now receive all promotions by mail.'));
    }
}