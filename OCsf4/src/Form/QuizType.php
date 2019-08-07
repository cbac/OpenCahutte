<?php

namespace App\Form; 

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuizType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$categoryChoices=array(
			'cg' => 'Culture générale', 
			'maths' => 'Mathématiques', 
			'phys' => 'Physique', 
			'hist' => 'Histoire'
		);
		$accesChoices=array(
			'public'=>'public',
			'privé'=>'privé'
		);
		$largeurChamps = array('style'=> 'width: 200px');
		
        $builder
			->add('nom',TextType::class, array(
				'label' => 'Nom du quiz', 
				'attr' => $largeurChamps
			))
			->add('category',ChoiceType::class, array(
				'choices' => $categoryChoices,
				'label' => 'Catégorie', 
				'attr' => $largeurChamps
			))
			->add('QCMs',CollectionType::class, array(
				'entry_type'   => QCMType::class,
				'allow_add'    => true,
				'allow_delete' => true
			))
			->add('save',SubmitType::class, array(
				'label' => 'Enregistrer', 
				'attr' => array(
					'class'=>'btn btn-primary'
				)
			))
        ;
		/* Should be managed with User identified or anonymous 
		if ($options['user'] != null) {
		    $builder->add('acces',ChoiceType::class, array(
				'choices' => $accesChoices,
				'label' => 'Acces', 
				'attr' => $largeurChamps
			));
		}
		*/
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Quiz',
			'user' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Form\QuizType';
    }
}
