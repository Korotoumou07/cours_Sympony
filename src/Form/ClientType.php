<?php

namespace App\Form;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surname',TextType::class,[

               'required'=>false
              
            ])
            ->add('telephone',TextType::class,[

               'required'=>false
               
            ])
            ->add('adresse',TextareaType::class,[

                "attr"=>[
                    'rows'=>6
                ],
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
            'data_class' => Client::class,
        ]);
    }
}