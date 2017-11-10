<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ExecutionType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
   {
       $builder
           ->add('inputMode', ChoiceType::class, array(
             'label' => "Entrée Standard",
             'choices' => array(
               'none' => 'Aucune',
               'it' => 'Interractive',
               'text' => 'Texte'
              ),
              'expanded' => true,
              'multiple' => false,
            )
           )
           ->add('inputs', TextareaType::class)
           ->add('compileOnly', CheckboxType::class, array(
            'label' => 'Compilation uniquement',
           ))
           ->add('launchParameters', TextType::class, array(
             'label' => 'Paramètres de lancement'
           ))
           ->add('files', HiddenType::Class);
   }

   public function configureOptions(OptionsResolver $resolver)
   {
      $resolver->setDefaults(
        array(
        'inherit_data' => true
        )
      );
    }

};
?>
