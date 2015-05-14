<?php

namespace OC\QuizgenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuizType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$nbQuestionsChoices=range(1,10);
		$categoryChoices=array(
			'cg' => 'Culture générale', 
			'maths' => 'Mathématiques', 
			'phys' => 'Physique', 
			'hist' => 'Histoire'
		);
		$typeChoices=array(
			'qcm' => 'QCM', 
			'qo' => 'Questions ouvertes'
		);
		
        $builder
			->add('date',			'date')
			->add('nom',			'text')
			->add('author', 		'text')
			->add('category',		'choice', array('choices' => $categoryChoices ))
			->add('type',			'choice', array('choices' => $typeChoices ))
			->add('nbQuestions',	'choice', array('choices' => $nbQuestionsChoices))
			->add('save',			'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\QuizgenBundle\Entity\Quiz'
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
