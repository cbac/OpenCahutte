<?php

namespace OC\QuizgenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QCMType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$bonneReponseChoices=array(1,2,3,4);
        $builder
            ->add('question',		'text')
			
            ->add('rep1',			'text')
            ->add('juste1',			'choice', array( 
				'choices' => array('vrai','faux'),
				'expanded' => true,
				'preferred_choices' => array('faux')
			))
			
            ->add('rep2',			'text')
            ->add('juste2',			'choice', array( 
				'choices' => array('vrai','faux'),
				'expanded' => true,
				'preferred_choices' => array('faux')
			))
			
            ->add('rep3',			'text')
            ->add('juste3',			'choice', array( 
				'choices' => array('vrai','faux'),
				'expanded' => true,
				'preferred_choices' => array('faux')
			))
			
            ->add('rep4',			'text')
            ->add('juste4',			'choice', array( 
				'choices' => array('vrai','faux'),
				'expanded' => true,
				'preferred_choices' => array('faux')
			))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\QuizgenBundle\Entity\QCM'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oc_quizgenbundle_qcm';
    }
}
