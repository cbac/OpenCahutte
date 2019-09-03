<?php

namespace App\Form;
use App\Entity\QCM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class QCMType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$paramChoice = array( 
			'label' => ' ', 
			'attr' => array('style'=> 'width: 300px'),
			'choices' => array('faux'=>false,'vrai'=>true),
			'expanded' => true
		);
		$largeurChamps = array('style'=> 'width: 300px;');
		
		$align = array('style' => 'text-align:center');
		
        $builder
            ->add('question',TextType::class, array(
				'label' => 'Question', 
				'attr' => $largeurChamps
			))
			
            ->add('rep1',TextType::class, array(
				'label' => 'A', 
				'attr' => $largeurChamps,
				'label_attr' => $align
			))
            ->add('juste1', ChoiceType::class, $paramChoice)
			
            ->add('rep2',TextType::class, array(
				'label' => 'B', 
				'attr' => $largeurChamps,
				'label_attr' => $align
			))
			->add('juste2',ChoiceType::class, $paramChoice)
			
            ->add('rep3',TextType::class, array(
				'label' => 'C', 
				'attr' => $largeurChamps,
				'label_attr' => $align
			))
			->add('juste3',ChoiceType::class, $paramChoice)
			
            ->add('rep4',TextType::class, array(
				'label' => 'D', 
				'attr' => $largeurChamps,
				'label_attr' => $align
			))
			->add('juste4',ChoiceType::class, $paramChoice)
            
            ->add('temps',IntegerType::class, array(
				'label' => 'Temps pour répondre (secondes)', 
				'label_attr' => array('style'=> 'width: 300px; text-align:left'),
				'attr' => array('style'=> 'width: 100px')
			))	
        ;
    }
    /**
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults ( array (
            'data_class' => QCM::class
        ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Form\QCMType';
    }
}
