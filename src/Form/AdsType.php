<?php

namespace App\Form;

use App\Entity\Ads;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('messages', TextareaType::class, [
                'label' => 'Сообщение',
                'attr' => [
                    'rows' => 10,
                    'maxlength' => 100,
                    'placeholder' => 'Введите сообщение',
                ],
                'required' => false,
            ])
            ->add('redirect', TextType::class, [
                'label' => 'Ссылка на страницу',
                'attr' => [
                    'placeholder' => 'Введите ссылку на страницу',
                ],
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'attr' => [
                    'placeholder' => 'Введите имя для пиара',
                ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Добавить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
        ]);
    }
}
