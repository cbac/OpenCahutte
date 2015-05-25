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
		$categoryChoices=array(
			'cg' => 'Culture générale', 
			'maths' => 'Mathématiques', 
			'phys' => 'Physique', 
			'hist' => 'Histoire'
		);
		
        $builder
			->add('nom',			'text')
			->add('author', 		'text')
			->add('category',		'choice', array('choices' => $categoryChoices ))
			->add('QCMs',			'collection', array(
				'type'         => new QCMType(),
				'allow_add'    => true,
				'allow_delete' => true
			))
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
