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
		$builder
            ->add('reponse', 'choice', array( 
				'label' => ' ', 
				'attr' => array('style'=> 'width: 400px'),
				'choices' => $this->options['reponses'],
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
