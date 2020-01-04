<?php
namespace Dashboard\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

class ProductInfoType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('price', TextType::class, array('required' => false, 'label' => 'Цена', 'attr' => array('class' => 'form-control')))
            ->add('year', TextType::class, array('required' => false, 'label' => 'Год выпуска', 'attr' => array('class' => 'form-control')))
            ->add('probeg', TextType::class, array('required' => false, 'label' => 'Пробег', 'attr' => array('class' => 'form-control')))
            ->add('wheel', CheckboxType::class, array('required' => false, 'label' => 'Правый руль'))
            ->add('isGasBaloon', CheckboxType::class, array('required' => false, 'label' => 'Газобалонное оборудование'))
            ->add('description', TextareaType::class, array('required' => false, 'label' => 'Описание', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('shape', 'entity', array('class' => 'DashboardCommonBundle:Shape',
                                              'choice_label' => 'title',
                                              'required' => false, 
                                              'label' => 'Состояние', 
                                              'attr' => array('class' => 'form-control')))
            ->add('owners', ChoiceType::class, array('choices' => array("Один" => "Один", "Два" => "Два", "Три" => "Три", "Больше трех" => "Больше трех"), 'required' => false, 'label' => 'Владельцы', 'attr' => array('class' => 'form-control')))
            ->add('vin', TextType::class, array('required' => false, 'label' => 'Vin номер', 'attr' => array('class' => 'form-control')))
            ->add('nds', CheckboxType::class, array('required' => false, 'label' => 'НДС включен'))
            ->add('torg', CheckboxType::class, array('required' => false, 'label' => 'Торг'))
            ->add('garant', TextType::class, array('required' => false, 'label' => 'Гарантия', 'attr' => array('class' => 'form-control')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\ProductInfo',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'info';
    }
}

