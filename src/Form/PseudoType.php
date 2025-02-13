<?php
namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PseudoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
		$largeurChamps = 'width: 200px';
		$builder
		->add('pseudojoueur', TextType::class, array(
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
        
        $resolver->setDefaults(array());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Form\PseudoType';
    }
}
