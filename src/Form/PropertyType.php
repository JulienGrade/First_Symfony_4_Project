<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat', ChoiceType::class,[
                'choices' => $this->getChoices()
            ]) 
            ->add('city')
            ->add('address')
            ->add('postal_code')
            ->add('sold')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms'
        ]);
    }
    private function getChoices()   // Ici on créé une méthode pour inverser les valeurs de heat ne pas retourner le chiffre mais la value 0=electric 1=gaz
    {                               // et ainsi l'afficher
        $choices = Property::HEAT;
        $output = [];
        foreach($choices as $key => $value){
            $output[$value] = $key;
        }
        return $output;
    }
}
