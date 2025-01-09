<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\ClassCard;
use App\Entity\TypeCard;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class CardType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
            ])
            ->add('generateName', ButtonType::class, [
                'label' => 'Générer un nom',
                'attr' => ['class' => 'btn generate-name'],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
                'label' => 'Image',
            ])
            ->add('attackPower', 
                null, 
                [
                    'label' => 'Puissance d\'attaque',
                    'attr' => ['min' => 1, 'max' => 9],
                    'help' => 'La puissance d\'attaque doit être comprise entre 1 et 9.',

                ]
            )
            ->add('type', EntityType::class, [
                'class' => TypeCard::class,
                'choice_label' => 'name',
                'label' => 'Type',
            ])
            ->add('class', EntityType::class, [
                'class' => ClassCard::class,
                'choice_label' => 'name',
                'label' => 'Classe',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
