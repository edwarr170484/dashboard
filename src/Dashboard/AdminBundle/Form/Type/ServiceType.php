<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Dashboard\AdminBundle\Form\Type\TranslationType;

class ServiceType extends AbstractType
{
    private $em;
    
    public function __construct($em) {
       $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('title', TextType::class, array('required' => true,'label' => 'Название услуги', 'attr' => array('class' => 'form-control', 'placeholder' => 'Название услуги')))
            ->add('icon', TextType::class, array('required' => false,'label' => 'Иконка', 'attr' => array('class' => 'form-control', 'placeholder' => 'Иконка')))
            ->add('description', TextareaType::class, array('required' => false,'label' => 'Описание услуги', 'attr' => array('class' => 'form-control tinyeditor', 'placeholder' => 'Описание услуги')))
            ->add('price', TextType::class, array('required' => true,'label' => 'Цена за услугу в баллах', 'attr' => array('class' => 'form-control', 'placeholder' => 'Цена за услугу в баллах')))
            ->add('days', TextType::class, array('required' => true,'label' => 'Количество дней на которое подключается услуга', 'attr' => array('class' => 'form-control', 'placeholder' => 'Количество дней на которое подключается услуга')))
            ->add('translations', 'collection', array('type' => new TranslationType($this->em), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Service'
        ));
    }
    
    public function getName()
    {
        return 'service';
    }
}




