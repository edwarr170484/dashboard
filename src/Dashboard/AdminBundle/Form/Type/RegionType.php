<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\Type\CityType;

class RegionType extends AbstractType
{   
    private $manager;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', TextType::class, array('required' => true, 'label' => 'Регион', 'attr' => array('class' => 'form-control','placeholder' => 'Регион')))
            ->add('city', CollectionType::class, array('type' => new CityType($this->manager), 'label' => ' ','allow_add' => true, 'allow_delete' => true, 'by_reference' => false,'attr' => array('class' => 'filter_values')))
            ->add('translations', 'collection', array('type' => new TranslationType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Region'
        ));
    }
    
    public function getName()
    {
        return 'region';
    }
}


