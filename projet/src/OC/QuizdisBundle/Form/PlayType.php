<?php

namespace OC\QuizdisBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
            ->add('reponse', 'choice', array( 
				'choices' => array('rep1','rep2','rep3','rep4'),
				'expanded' => true,
				'multiple' => true
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
            'data_class' => 'OC\QuizdisBundle\Entity\ReponseQuestion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oc_quizdisbundle_play';
    }
}
