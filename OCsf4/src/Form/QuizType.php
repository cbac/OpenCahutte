<?php

namespace App\Form; 

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$categoryChoices=array(
			'cg' => 'Culture générale', 
			'maths' => 'Mathématiques', 
			'phys' => 'Physique', 
			'hist' => 'Histoire'
		);
		$accesChoices=array(
			'public'=>'public',
			'privé'=>'privé'
		);
		$largeurChamps = array('style'=> 'width: 200px');
		
        $builder
			->add('nom','text', array(
				'label' => 'Nom du quiz', 
				'attr' => $largeurChamps
			))
			->add('category','choice', array(
				'choices' => $categoryChoices,
				'label' => 'Catégorie', 
				'attr' => $largeurChamps
			))
			->add('QCMs','collection', array(
				'type'         => new QCMType(),
				'allow_add'    => true,
				'allow_delete' => true
			))
			->add('save','submit', array(
				'label' => 'Enregistrer', 
				'attr' => array(
					'class'=>'btn btn-primary'
				)
			))
        ;
		
		if ($options['user'] != null) {
			$builder->add('acces','choice', array(
				'choices' => $accesChoices,
				'label' => 'Acces', 
				'attr' => $largeurChamps
			));
		}
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\QuizgenBundle\Entity\Quiz',
			'user' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oc_quizgenbundle_quiz';
    }
}
