<?php
 namespace Album\Form;

 use Zend\Form\Form;

 class AlbumForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('album');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'title',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Titre',
             ),
         ));
         $this->add(array(
             'name' => 'artist',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Créateur',
             ),
         ));
		 $this->add(array(
            'name' => 'nbquestions', 
            'type' => 'Text', 
            'options' => array( 
                'label' => 'Nombre de questions', 
            ), 
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }