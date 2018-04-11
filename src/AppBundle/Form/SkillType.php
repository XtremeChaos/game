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
            ->add('name', TextType::class)
            ->add('chance', TextType::class)
            ->add('className', ChoiceType::class, ['choices' => ['Magic Shield' => 'MagicShield','Rapid Strike' => 'RapidStrike','Berserk' => 'Berserk']])
            ->add('type', ChoiceType::class, ['choices' => ['Atac' => 'attack','Aparare' => 'defence']])
            ->add('description', TextType::class)
            ->add('save', SubmitType::class)
        ;
    }
}