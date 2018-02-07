<?php 
 
namespace MainBundle\Form; 
 
use Symfony\Component\Form\AbstractType; 
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\OptionsResolver\OptionsResolver; 
 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\Extension\Core\Type\NumberType; 

use MainBundle\Entity\User;
 
class OptionsInterfaceType extends AbstractType 
{ 
 
    public function buildForm(FormBuilderInterface $builder, array $options) 
    { 
        $builder 
            ->add('aceTheme', ChoiceType::class, array( 
                'label' => "Thème de l'éditeur", 
                'choices' => array( 
                    'tomorrow_night' => 'tomorrow_night', 
                    'clouds' => 'clouds', 
                    'ambiance' => 'ambiance' 
                ), 
            )) 
            ->add('consoleTheme', ChoiceType::class, array( 
                'label' => "Thème de la console", 
                'choices' => array( 
                    'dark' => 'sombre', 
                    'light' => 'claire', 
                    'blue' => 'bleu' 
                ), 
            )) 
            ->add('sizeEditeur', NumberType::class, array( 
                'label' => 'Taille de la police (editeur)' 
            )) 
        ;
    } 
 
    public function configureOptions(OptionsResolver $resolver) 
    { 
        $resolver->setDefaults(array( 
            'data_class' => User::class, 
        )); 
    } 
 
    /** 
        * {@inheritdoc} 
        */ 
    public function getBlockPrefix() 
    { 
        return 'mainbundle_options_interface'; 
    } 
}; 
?> 

