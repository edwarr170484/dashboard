<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Dashboard\AdminBundle\Form\Type\TranslationType;

class CategoryType extends AbstractType
{
    private $em;
    private $parent;
    
    public function __construct($em, $parent) {
       $this->em = $em;
       $this->parent = $parent;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('title', TextType::class, array('required' => true,'label' => 'Название категории', 'attr' => array('class' => 'form-control')))
            ->add('name', TextType::class, array('required' => true,'label' => 'Транслит(SEO)', 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('required' => false, 'label' => 'Описание', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('isShowFilters', CheckboxType::class, array('required' => false, 'label' => 'Показывать фильтры', 'attr' => array('class' => 'form-control')))
            ->add('isShowBu', CheckboxType::class, array('required' => false, 'label' => 'Показывать выбор "Б/У"', 'attr' => array('class' => 'form-control')))
            ->add('isShowPriceFilter', CheckboxType::class, array('required' => false, 'label' => 'Включить фильтр по цене', 'attr' => array('class' => 'form-control')))
            ->add('imageNew', 'file', array('required' => false, 'label' => 'Изображение','mapped' => false, 'attr' => array('class' => 'form-control')))
            ->add('image', 'hidden', array('required' => true, 'label' => ''))
            ->add('parent', 'entity', array('class' => 'DashboardCommonBundle:Category', 
                                            'choice_label' => 'title',
                                            'placeholder' => 'Нет',
                                            'label' => 'Родительская категория',
                                            'group_by' => 'parent.title',
                                            'required' => false,
                                            'attr' => array('class' => 'form-control')))
            ->add('metaTagTitle', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Title', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Title')))
            ->add('metaTagDescription', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Description', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Description')))
            ->add('metaTagAuthor', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Author', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Author')))
            ->add('metaTagRobots', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Robots', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Robots')))
            ->add('metaTagKeywords', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Keywords', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Keywords')))
            /*->add('filters', 'entity', array( 'class' => 'DashboardCommonBundle:Filter','choice_label' => 'name','label' => 'Привязанные фильтры','expanded' => true, "multiple" => true))*/
            ->add('translations', 'collection', array('type' => new TranslationType($this->em), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('descriptions', 'collection', array('type' => new DescriptionType($this->em), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Category'
        ));
    }
    
    public function getName()
    {
        return 'category';
    }
}

