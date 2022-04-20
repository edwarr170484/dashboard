<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Dashboard\AdminBundle\Form\DataTransformer\RegionToNumberTransformer;
use Dashboard\AdminBundle\Form\Type\TranslationType;

class CityType extends AbstractType
{   
    private $manager;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', TextType::class, array('required' => true, 'label' => 'Город', 'attr' => array('class' => 'form-control','placeholder' => 'Город')))
            ->add('translations', 'collection', array('type' => new TranslationType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('sortorder', TextType::class, array('required' => false,'label' => 'Порядок', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('region', 'hidden')->addModelTransformer(new RegionToNumberTransformer($this->manager)));    
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\City'
        ));
    }
    
    public function getName()
    {
        return 'city';
    }
}




