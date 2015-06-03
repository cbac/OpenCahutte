<?php

namespace OC\QuizdisBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayType extends AbstractType
{
	public function __construct($options = null) {
        $this->options = $options;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$rep=$this->options['rep'];
		$builder
			->add('save','submit', array('label' => $rep))
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
