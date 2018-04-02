<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('className', ChoiceType::class, ['choices' => ['Magic Shield' => 'MagicShield','Rapid Strike' => 'RapidStrike','Berserk' => 'Berserk'], 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('description', TextType::class, ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class, ['label' => 'Update Skill','attr' => ['class' => 'btn btn-primary', 'style' => 'margin-bottom:15px']])
        ;
    }
}