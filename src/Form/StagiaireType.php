<?php

namespace App\Form;

use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label'=>"Nom:"])
            ->add('prenom', TextType::class,['label'=>"Prénom:"])
            ->add('telephone', TextType::class,['label'=>"N°:"])
            ->add('adresse', TextType::class, ['label'=>"Adresse:"])
            ->add('diplome', TextType::class, ['label'=>"Diplôme:"])
            ->add('contrat', CheckboxType::class,['label'=>"Contrat:", 'required'=>false])
            ->add('photo',FileType::class,['label'=>"Télécharger un fichier",'data_class'=>null, 'required'=>false])
            ->add('description',TextType::class, ['label'=>"Veuillez émettre un commentaire"])
            ->add('soumettre', SubmitType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}