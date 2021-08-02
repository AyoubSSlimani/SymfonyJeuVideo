<?php


namespace App\Form;


use App\Entity\PostCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [

            ])
            ->add('lastName', TextType::class, [

            ])
            ->add('typeDeDemande', TextType::class, [

            ])
            ->add('telephone', IntegerType::class, [

            ])
            ->add('mail', TextType::class, [

            ])
            ->add('votreMessage', TextType::class, [

            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostCategory::class,
        ]);
    }
}