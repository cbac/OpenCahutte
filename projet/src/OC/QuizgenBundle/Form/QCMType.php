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
		$paramChoice = array( 
			'label' => ' ', 
			'attr' => array('style'=> 'width: 300px'),
			'choices' => array(0=>'faux',1=>'vrai'),
			'expanded' => true
		);
		$largeurChamps = array('style'=> 'width: 300px;');
		
		$align = array('style' => 'text-align:center');
		
        $builder
            ->add('question','text', array(
				'label' => 'Question', 
				'attr' => $largeurChamps
			))
			
            ->add('rep1','text', array(
				'label' => 'A', 
				'attr' => $largeurChamps,
				'label_attr' => $align
			))
            ->add('juste1','choice', $paramChoice)
			
            ->add('rep2','text', array(
				'label' => 'B', 
				'attr' => $largeurChamps,
				'label_attr' => $align
			))
            ->add('juste2','choice', $paramChoice)
			
            ->add('rep3','text', array(
				'label' => 'C', 
				'attr' => $largeurChamps,
				'label_attr' => $align
			))
            ->add('juste3','choice', $paramChoice)
			
            ->add('rep4','text', array(
				'label' => 'D', 
				'attr' => $largeurChamps,
				'label_attr' => $align
			))
            ->add('juste4','choice', $paramChoice)
            
            ->add('temps','integer', array(
				'label' => 'Temps pour répondre (secondes)', 
				'label_attr' => array('style'=> 'width: 300px; text-align:left'),
				'attr' => array('style'=> 'width: 100px')
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
