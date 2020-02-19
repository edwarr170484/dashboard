<?php
namespace Dashboard\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProductServiceType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('service', 'entity', array('class' => 'DashboardCommonBundle:Service',
                                              'choice_label' => 'title',
                                              'required' => true, 
                                              'label' => 'Услуга', 
                                              'attr' => array('class' => 'form-control')))
            ->add('dateAdded', DateType::class, array('required' => false, 'label' => 'Дата начала действия', 'attr' => array('class' => 'form-control')))
            ->add('dateEnd', DateType::class, array('required' => false, 'label' => 'Дата окончания действия', 'attr' => array('class' => 'form-control')))
            ->add('count', TextType::class, array('required' => false, 'label' => 'Количество единиц услуги', 'attr' => array('class' => 'form-control')))
            ->add('isActive', CheckboxType::class, array('required' => false, 'label' => 'Активна'));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\ProductService',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'service';
    }
}

