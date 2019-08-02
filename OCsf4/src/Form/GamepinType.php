<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GamepinType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
		$largeurChamps = 'width: 200px';
		$builder
			->add('gamepin', 'text', array(
				'attr' => array(
					'style'=> $largeurChamps,
				)
			))
			->add('save', 'submit', array(
				'label' => 'GO !', 
				'attr' => array(
					'style'=> $largeurChamps,
					'class'=> 'btn btn-primary'
				)
			))
		;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oc_quizdisbundle_gamepin';
    }
}
