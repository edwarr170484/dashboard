<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Dashboard\CommonBundle\Form\DataTransformer\QuestionToNumberTransformer;

class RateServiceType extends AbstractType
{   
    private $manager;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', TextType::class, array('required' => true,'label' => 'Вопрос', 'attr' => array('class' => 'form-control')))
            ->add('content', TextareaType::class, array('required' => true,'label' => 'Ответ', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add($builder->create('question', 'hidden')->addModelTransformer(new QuestionToNumberTransformer($this->manager)));    
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\QuestionAnswer'
        ));
    }
    
    public function getName()
    {
        return 'answer';
    }
}