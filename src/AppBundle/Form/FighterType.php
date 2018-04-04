<?php
namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Skill;
use AppBundle\Entity\Fighter;

class FighterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('health', TextType::class)
            ->add('strength', TextType::class)
            ->add('defence', TextType::class)
            ->add('speed', TextType::class)
            ->add('luck', TextType::class)
            ->add('type', ChoiceType::class, ['choices' => ['Hero' => 'hero','Beast' => 'beast']])
            ->add('skills',EntityType::class,[
                'class' => Skill::class,
                'choice_label' => 'name',
                'label' => 'Skilluri',
                'expanded' => true,
                'multiple' => true
            ])
            ->add('save', SubmitType::class, ['label' => 'Update Fighter','attr' => ['class' => 'btn btn-primary']])
        ;
    }
}