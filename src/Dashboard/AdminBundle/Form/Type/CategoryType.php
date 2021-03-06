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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\Type\GenerationType;
use Dashboard\AdminBundle\Form\Type\CategoryRateType;

class CategoryType extends AbstractType
{
    private $em;
    private $category;
    
    public function __construct($em, $category) {
       $this->em = $em;
       $this->category = $category;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('title', TextType::class, array('required' => true,'label' => 'Название категории', 'attr' => array('class' => 'form-control')))
            ->add('hTitle', TextType::class, array('required' => false,'label' => 'Название категории h1', 'attr' => array('class' => 'form-control')))
            ->add('name', TextType::class, array('required' => true,'label' => 'Транслит(SEO)', 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('required' => false, 'label' => 'Описание', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('isShowFilters', CheckboxType::class, array('required' => false, 'label' => 'Показывать фильтры', 'attr' => array('class' => 'form-control')))
            ->add('isUseChildrensLikeMark', CheckboxType::class, array('required' => false, 'label' => 'Использовать дочерние категории как фильтр по маркам', 'attr' => array('class' => 'form-control')))
            ->add('isUseChildrensLikeModel', CheckboxType::class, array('required' => false, 'label' => 'Использовать дочерние категории как фильтр по моделям', 'attr' => array('class' => 'form-control')))
            ->add('isUseChildrensLikeType', CheckboxType::class, array('required' => false, 'label' => 'Использовать дочерние категории как фильтр по типам', 'attr' => array('class' => 'form-control')))    
            ->add('isShowGenerationFilter', CheckboxType::class, array('required' => false, 'label' => 'Показывать фильтр "Поколение" для категории', 'attr' => array('class' => 'form-control')))
            ->add('isBreakRedirect', CheckboxType::class, array('required' => false, 'label' => 'Не переходить на страницу категории из главного меню', 'attr' => array('class' => 'form-control')))
            ->add('isShowPriceFilter', CheckboxType::class, array('required' => false, 'label' => 'Включить фильтр по цене', 'attr' => array('class' => 'form-control')))
            ->add('image', TextareaType::class, array('required' => false, 'label' => 'Код изображения SVG', 'attr' => array('class' => 'form-control')))
            ->add('parent', 'entity', array('class' => 'DashboardCommonBundle:Category', 
                                            'choice_label' => 'title',
                                            'placeholder' => 'Нет',
                                            'label' => 'Родительская категория',
                                            'group_by' => 'parent.title',
                                            'required' => false,
                                            'attr' => array('class' => 'form-control')))
            ->add('yearFrom', TextType::class, array('required' => false,'label' => 'Год начала выпуска', 'attr' => array('class' => 'form-control')))
            ->add('yearTo', TextType::class, array('required' => false,'label' => 'Год окончания выпуска', 'attr' => array('class' => 'form-control')))
            ->add('metaTagTitle', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Title', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Title')))
            ->add('metaTagDescription', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Description', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Description')))
            ->add('metaTagAuthor', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Author', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Author')))
            ->add('metaTagRobots', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Robots', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Robots')))
            ->add('metaTagKeywords', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Keywords', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Keywords')))
            /*->add('filters', 'entity', array( 'class' => 'DashboardCommonBundle:Filter','choice_label' => 'name','label' => 'Привязанные фильтры','expanded' => true, "multiple" => true))*/
            ->add('translations', 'collection', array('type' => new TranslationType($this->em), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('generations', 'collection', array('type' => new GenerationType($this->em, $this->category->getGenerations()), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('descriptions', 'collection', array('type' => new DescriptionType($this->em), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('rates', 'collection', array('type' => new CategoryRateType($this->em), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('formType', ChoiceType::class, array('choices' => array("1" => "По шагам", "2" => "Одной формой"),'required' => false, 'label' => 'Форма при добавлении объявления в категорию', 'attr' => array('class' => 'form-control')))
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

