<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Dish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        dump($options);
        $builder
            ->add('name', null, [
                'required' => true
            ])
            ->add('calories',ChoiceType::class, [
                'choices'  => $options['calorieValues']
            ])
            ->add('price', null, [
                'required' => true,
                'attr' => array('type'=>'number')
            ])
            ->add('image',null,[
                'empty_data' => 'http://via.placeholder.com/360x225'
            ])
            ->add('description', null, [
                'required' => true
            ])
            ->add('sticky')
            ->add('category', null, [
                'required' => true
            ])
            ->add('user', null, [
                'required' => true
            ])
            ->add('allergens')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dish::class,
            'calorieValues' => array()
        ]);
    }
}
