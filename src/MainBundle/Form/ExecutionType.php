<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ExecutionType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
   {
       $builder
           ->add('inputMode', ChoiceType::class, array(
             'choices' => array(
               'none' => 'None',
               'it' => 'Interractive',
               'text' => 'Text : '
              ),
              'expanded' => true,
              'multiple' => false,
            )
           )
           ->add('inputs', TextareaType::class)
           ->add('compileOnly', CheckboxType::class, array(
            'label' => 'Compilation uniquement',
           ));
   }

};
?>
