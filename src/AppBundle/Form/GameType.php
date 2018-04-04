<?php
namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use AppBundle\Entity\Fighter;
use AppBundle\Entity\GameFighter;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('maxRound', NumberType::class)
//            ->add('gameFighters',EntityType::class,[
//                'class' => GameFighter::class,
//                'choice_label' => 'team',
//                'label' => 'Fighters',
//                'expanded' => true,
//                'multiple' => true
//            ])
            ->add('save', SubmitType::class, ['label' => 'Update Game','attr' => ['class' => 'btn btn-primary']])
        ;
    }
}