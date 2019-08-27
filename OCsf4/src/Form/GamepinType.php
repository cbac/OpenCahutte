<?php
namespace App\Form;

use App\Entity\Gamepin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
			->add('pinNumber', TextType::class, array(
				'attr' => array(
					'style'=> $largeurChamps,
				)
			))
			->add('save', SubmitType::class, array(
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
    public function configureOptions(OptionsResolver $resolver) {
               $resolver->setDefaults([
                'data_class' => Gamepin::class,
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oc_quizdisbundle_gamepin';
    }
}
