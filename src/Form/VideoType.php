<?php

namespace App\Form;

use App\Controller\PlayerController;
use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'title'],
                'attr' => ['autofocus' => true]
            ])
            ->add('year', TextType::class, [
                'label' => 'Année',
                'label_attr' => ['class' => 'title'],
            ])
            ->add('external_id', TextType::class, [
                'label' => 'ID externe',
                'label_attr' => ['class' => 'title'],
            ])
            ->add('external_url', TextType::class, [
                'label' => 'URL externe',
                'label_attr' => ['class' => 'title'],
            ])
            ->add('flag', ChoiceType::class, [
                'label' => 'flags',
                'label_attr' => ['class' => 'title'],
                'choices' => array_flip(PlayerController::FLAGS),
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Miniature',
                'label_attr' => ['class' => 'title'],
                'mapped' => false,
                'attr' => [
                    'class' => 'no-title',
                    'accept' => "image/jpeg, image/png"
                ]
            ])
            ->add('id', HiddenType::class, [
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
