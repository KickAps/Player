<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'title'],
                'attr' => ['autofocus' => true]
            ])
            ->add('path', TextType::class, [
                'label' => 'Vidéo',
                'label_attr' => ['class' => 'title'],
            ])
            ->add('year', TextType::class, [
                'label' => 'Année',
                'label_attr' => ['class' => 'title'],
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Miniature',
                'label_attr' => ['class' => 'title'],
                'mapped' => false,
                'attr' => [
                    'class' => 'no-title',
                    'accept' => "image/jpeg, image/png"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
