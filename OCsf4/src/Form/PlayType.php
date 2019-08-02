<?php
namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$rep=$options['rep'];
		$class=$options['class'];
		$builder
			->add('reponseDonnee','hidden', array('data' => $rep))
			->add('save','submit', array(
				'label' => $rep, 
				'attr' => array(
					'style'=> 'width: 100%; height:100%;  margin:2px; font-size:60px;',
					'class'=>$class
				)
			))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\QuizdisBundle\Entity\ReponseQuestion',
			'rep' => null,
			'class' => 'btn btn-primary'
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
