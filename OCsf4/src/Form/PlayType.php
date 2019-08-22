<?php
namespace App\Form;

use App\Entity\ReponseQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
			->add('reponseDonnee',HiddenType::class, array('data' => $rep))
			->add('save',SubmitType::class, array(
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
    public function configureOptions(OptionsResolver $resolver) 
        {
        $resolver->setDefaults(array(
            'data_class' => ReponseQuestion::class,
			'rep' => null,
			'class' => 'btn btn-primary'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Form\PlayType';
    }
}
