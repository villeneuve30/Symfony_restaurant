<?php

namespace App\Form;

use App\Entity\ClientOrder;
use App\Entity\ClientTable;
use App\Entity\Dish;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serveur', EntityType::class,  [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('table', EntityType::class,  [
                'class' => ClientTable::class,
                'choice_label' => 'numero',
            ])
            ->add('dish', EntityType::class,  [
                'class' => Dish::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('price', NumberType::class, [
                'data' => '0',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Commandé' => 'prise',
                    'Preparée' => 'preparee',
                    'Servie' => 'servie',
                    'Réglé' => 'payee',
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientOrder::class,
        ]);
    }
}
