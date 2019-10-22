<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Dashboard\AdminBundle\Form\DataTransformer\PackToNumberTransformer;

class PackServiceType extends AbstractType
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
            ->add('label', TextType::class, array('required' => false,'label' => 'Текст для надписи', 'attr' => array('class' => 'form-control')))
            ->add('sortorder', TextType::class, array('required' => true,'label' => 'Порядок отображение в пакете', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('pack', 'hidden')->addModelTransformer(new PackToNumberTransformer($this->manager)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\PackService'
        ));
    }
    
    public function getName()
    {
        return 'service';
    }
}


