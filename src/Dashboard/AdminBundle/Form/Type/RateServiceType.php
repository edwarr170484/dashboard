<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Dashboard\AdminBundle\Form\DataTransformer\RateToNumberTransformer;

class RateServiceType extends AbstractType
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
                             'label' => 'Услуга:', 'attr' => array('class' => 'form-control')))
            ->add('value', TextType::class, array('required' => true,'label' => 'Количество единиц услуги', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('rate', 'hidden')->addModelTransformer(new RateToNumberTransformer($this->manager)));    
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\RateService'
        ));
    }
    
    public function getName()
    {
        return 'rateservice';
    }
}




