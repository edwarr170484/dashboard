<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\ServiceToNumberTransformer;

class ProductServiceType extends AbstractType
{
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add($builder->create('product', 'hidden')->addModelTransformer(new ProductToNumberTransformer($this->manager)))
            ->add($builder->create('service', 'hidden')->addModelTransformer(new ServiceToNumberTransformer($this->manager)))
            ->add('save', ButtonType::class, array('label' => 'Pasūtīt pakalpojumu', 'attr' => array('class' => 'send-tab-form')));
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
        return 'productService';
    }
}



