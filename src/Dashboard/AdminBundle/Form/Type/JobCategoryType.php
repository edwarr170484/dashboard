<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Dashboard\AdminBundle\Form\Type\JobType;

class JobCategoryType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', TextType::class, array('required' => true,'label' => 'Название', 'attr' => array('class' => 'form-control')))
            ->add('icon', TextareaType::class, array('required' => false,'label' => 'Иконка', 'attr' => array('class' => 'form-control')))    
            ->add('sortorder', TextType::class, array('required' => false,'label' => 'Порядок', 'attr' => array('class' => 'form-control'))) 
            ->add('imageNew', 'file', array('required' => false, 'label' => 'Изображение','mapped' => false, 'attr' => array('class' => 'form-control')))
            ->add('image', 'hidden', array('required' => false))
            ->add('jobs', CollectionType::class, array('type' => new JobType($this->manager), 'label' => ' ','allow_add' => true, 'allow_delete' => true, 'by_reference' => false))    
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\JobCategory'
        ));
    }
    
    public function getName()
    {
        return 'jobcategory';
    }
}