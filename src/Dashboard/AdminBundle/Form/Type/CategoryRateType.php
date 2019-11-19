<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Dashboard\AdminBundle\Form\DataTransformer\CategoryToNumberTransformer;

class CategoryRateType extends AbstractType
{   
    private $manager;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('billId', TextType::class, array('required' => true,'label' => 'ID тарифа для счетов', 'attr' => array('class' => 'form-control')))
            ->add('price', TextType::class, array('required' => true,'label' => 'Цена', 'attr' => array('class' => 'form-control')))   
            ->add('rate', 'entity', array('class' => 'DashboardCommonBundle:Rate',
                             'choice_label' => 'name',
                             'required' => true,
                             'label' => 'Тариф:', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('category', 'hidden')->addModelTransformer(new CategoryToNumberTransformer($this->manager)));    
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\CategoryRate'
        ));
    }
    
    public function getName()
    {
        return 'categoryrate';
    }
}




