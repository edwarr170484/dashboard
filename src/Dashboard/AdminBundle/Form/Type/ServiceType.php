<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\Type\ServicePriceType;

class ServiceType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('title', TextType::class, array('required' => true,'label' => 'Название услуги', 'attr' => array('class' => 'form-control')))
            ->add('icon', TextareaType::class, array('required' => false,'label' => 'Иконка', 'attr' => array('class' => 'form-control')))
            ->add('iconGray', TextareaType::class, array('required' => false,'label' => 'Иконка для плашек', 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('required' => false,'label' => 'Описание услуги', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('days', TextType::class, array('required' => true,'label' => 'Количество единиц измерения на которое подключается услуга', 'attr' => array('class' => 'form-control')))
            ->add('parameter', ChoiceType::class, array('choices' => array("days" => "Дней", "count" => "Раз"),'required' => true,'label' => 'Единица измерения услуги', 'attr' => array('class' => 'form-control')))
            ->add('isButton', CheckboxType::class, array('required' => false,'label' => 'Рассматривать как кнопку в плашке объявления', 'attr' => array('class' => 'form-control')))    
            ->add('translations', 'collection', array('type' => new TranslationType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('prices', CollectionType::class, array('type' => new ServicePriceType($this->manager), 'label' => ' ','allow_add' => true, 'allow_delete' => true, 'by_reference' => false))    
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




