<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\AdminBundle\Form\Type\FilterType;
use Dashboard\AdminBundle\Form\Type\TranslationType;

class FilterType extends AbstractType
{   
    private $manager;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', TextType::class, array('required' => true,'label' => 'Название фильтра', 'attr' => array('class' => 'form-control', 'placeholder' => 'Название фильтра')))
            ->add('type', ChoiceType::class, array('choices' => array(
                                                                      "select" => "Список", 
                                                                      "radio" => "Радиокнопка", 
                                                                      "checkbox" => "Чекбокс",
                                                                      "region_select" => "Диапазон с выбором из списков",
                                                                      "selectable" => "Выборка по значению",
                                                                      "input" => "Ввод от руки"), 
                                                                      'required' => true, 
                                                                      'label' => 'Тип фильтра', 'attr' => array('class' => 'form-control', 'placeholder' => 'Тип фильтра')))
            ->add('values', CollectionType::class, array('type' => new FilterValueType($this->manager), 'label' => ' ','allow_add' => true, 'allow_delete' => true, 'by_reference' => false,
                                               'attr' => array('class' => 'filter_values')))
            ->add('categories', 'entity', array( 'class' => 'DashboardCommonBundle:Category',
                                                 'choice_label' => 'title',
                                                 'required' => false,
                                                 'label' => 'Привязанные категории (для выбора нескольких зажмите Ctrl)', 
                                                 'multiple' => true,
                                                 'placeholder' => 'Нет',
                                                 'attr' => array('class' => 'form-control')))
            ->add('translations', 'collection', array('type' => new TranslationType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('isRequired', CheckboxType::class, array('required' => false,'label' => 'Обязательно для заполнения при добавлении объявления'))
            ->add('isSearch', CheckboxType::class, array('required' => false,'label' => 'Показывать в форме поиска'))
            ->add('isSelltype', CheckboxType::class, array('required' => false,'label' => 'Назначить фильтр как "Тип сделки" для категории'))
            ->add('isShowCard', CheckboxType::class, array('required' => false,'label' => 'Показывать значение фильтра в карточке объявления'))
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Filter'
        ));
    }
    
    public function getName()
    {
        return 'filter';
    }
}


