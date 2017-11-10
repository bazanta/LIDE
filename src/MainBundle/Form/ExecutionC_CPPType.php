<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use MainBundle\Entity\ExecutionC_CPP;

class ExecutionC_CPPType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options){
    $builder
      ->add(
        'warningLevel', ChoiceType::class, array(
          'choices' => array(
            'wall' => 'Many (-Wall)',
            'wextra' => 'Extra (-Wextra)',
            'wpedantic' => 'Pedantic (-Wpedantic)'
          ),
          'expanded' => true,
          'multiple' => true //CHECKBOX
        )
      )
      ->add(
        'optimisationLevel', ChoiceType::class, array(
          'choices' => array(
            'o0' => 'None (-O0)',
            'o1' => 'Minimum (-O1)',
            'o2' => 'Full (-O2)',
            'o3' => 'Maximum (-O3)'
          ),
          'expanded' => true,
          'multiple' => false //SELECT
        )
      );

      $builder->add( 'baseOption', ExecutionType::class, array(
                'data_class' => ExecutionC_CPP::class
              ));
  }
}
