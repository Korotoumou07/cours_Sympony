<?php

namespace App\Form;

use App\Entity\Dette;
use App\Entity\Client;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant',NumberType::class,[
                'required'=>false
            ])
            ->add('createAt', null, [
                'widget' => 'single_text',
                'required'=>false
            ])
            ->add('montantVerser',NumberType::class,[
                'required'=>false
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id',
                'required'=>false
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-dark'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dette::class,
        ]);
    }
}
