<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add("email", EmailType::class)
            ->add("gender", null, [
                "label" => "Genre",
            ])
            ->add("lastname", null, [
                "label" => "Nom",
            ])
            ->add("firstname", null, [
                "label" => "Prénom",
            ])
            ->add("birthdate", DateType::class, [
                "widget" => "single_text",
                "label" => "Date de naissance",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => User::class,
        ]);
    }
}
