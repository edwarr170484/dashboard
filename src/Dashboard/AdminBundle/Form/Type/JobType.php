<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Dashboard\AdminBundle\Form\DataTransformer\JobcategoryToNumberTransformer;

class JobType extends AbstractType
{   
    private $manager;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', TextType::class, array('required' => true,'label' => 'Название', 'attr' => array('class' => 'form-control')))    
            ->add('sortorder', TextType::class, array('required' => true,'label' => 'Порядок', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('category', 'hidden')->addModelTransformer(new JobcategoryToNumberTransformer($this->manager)));    
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Job'
        ));
    }
    
    public function getName()
    {
        return 'categoryjob';
    }
}