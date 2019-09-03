<?php

namespace App\Form; 

use App\Entity\Quiz;
use App\Entity\QCM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use App\Entity\Access;

class QuizType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$categoryChoices=array(
			'General Knowledge' => 'Culture générale', 
			'Maths' => 'Maths', 
			'Physics' => 'Physics', 
			'History' => 'History',
		    'Computer Science'=>'Computer Science'
		);

		$largeurChamps = array('style'=> 'width: 200px');
		
        $builder
			->add('nom',TextType::class, array(
				'label' => 'Quiz Title', 
				'attr' => $largeurChamps
			))
			->add('access',EntityType::class, array(
			    'class'=>Access::class,
				'label' => 'Access', 
				'attr' => $largeurChamps
			))
			->add('category',ChoiceType::class, array(
			    'choices' => $categoryChoices,
			    'label' => 'Category',
			    'attr' => $largeurChamps
			))
			->add('QCMs', CollectionType::class, array(
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
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => Quiz::class
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
