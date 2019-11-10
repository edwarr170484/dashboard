<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\Type\PackPriceType;

class PackType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', TextType::class, array('required' => true,'label' => 'Название', 'attr' => array('class' => 'form-control')))
            ->add('label', TextType::class, array('required' => false,'label' => 'Метка', 'attr' => array('class' => 'form-control')))
            ->add('bgClass', TextType::class, array('required' => false,'label' => 'Класс блока при отображении на сайте', 'attr' => array('class' => 'form-control')))
            ->add('price', TextType::class, array('required' => true,'label' => 'Цена', 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('required' => false,'label' => 'Описание', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('translations', 'collection', array('type' => new TranslationType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('services', 'collection', array('type' => new PackServiceType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('prices', CollectionType::class, array('type' => new PackPriceType($this->manager), 'label' => ' ','allow_add' => true, 'allow_delete' => true, 'by_reference' => false))    
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Pack'
        ));
    }
    
    public function getName()
    {
        return 'pack';
    }
}




