<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class VideoType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title')
            ->add('path', FileType::class, [
                'label' => 'Vidéo',
                'label_attr' => ['class' => 'title'],
                'mapped' => false,
                'attr' => [
                    'class' => 'no-title',
                    'accept' => "video/mp4"
                ]
            ])
            ->add('year')
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
