<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class APIType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apiKey', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('id')
            ->add('name')
            ->add('author')
            ->add('downloadable', CheckboxType::class, [
                'empty_data' => false,
                'data' => false,
                'required' => false,
                'false_values' => ['false', 0, false],
            ])
            ->add('last_read', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'csrf_protection' => false,
        ]);
    }
}
