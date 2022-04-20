<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Form\Type\ProductFotoType;
use Dashboard\CommonBundle\Entity\Selltype;

use Dashboard\CommonBundle\Form\DataTransformer\CategoryToNumberTransformer;

use Dashboard\CommonBundle\Entity\Category;
use Dashboard\CommonBundle\Entity\CategoryRepository;

class EditProductType extends AbstractType
{
    private $em;
    private $user;
    
    public function __construct($em, $user) {
       $this->em = $em;
       $this->user = $user;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('authorName', TextType::class, array('required' => true, 'data' => $this->user->getFirstname(),'label' => 'Ваше имя', 'attr' => array('class' => 'form-control')))
            ->add('authorEmail', EmailType::class, array('required' => true, 'data' => $this->user->getUser()->getEmail() ,'label' => 'Ваш E-MAIL', 'attr' => array('class' => 'form-control')))
            ->add('authorPhone', TextType::class, array('required' => true, 'label' => 'Контактный телефон ', 'attr' => array('class' => 'form-control')))
            ->add('region', 'entity', array('class' => 'DashboardCommonBundle:Region','choice_label' => 'name','required' => false, 'label' => 'регион', 'attr' => array('class' => 'hidden-input form-control','id' => 'authorRegionId','placeholder' => 'регион')))
            ->add('city', 'entity', array('class' => 'DashboardCommonBundle:City','choice_label' => 'name','required' => true, 'label' => 'город', 'attr' => array('class' => 'hidden-input form-control','id' => 'authorCityId','placeholder' => 'город')))
            ->add('typeno', CheckboxType::class, array('required' => false, 'label' => 'нет', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typeno data-radio=type')))
            ->add('typenew', CheckboxType::class, array('required' => false, 'label' => 'новое', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typenew data-radio=type')))
            ->add('typebu', CheckboxType::class, array('required' => false, 'label' => 'б/у', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typebu data-radio=type')))
            ->add('selltype', 'entity', array('class' => 'DashboardCommonBundle:Selltype',
                                              'choice_label' => 'title',
                                              'required' => true, 'label' => 'тип объявления', 
                                              'attr' => array('class' => 'hidden-input form-control','id' => 'selltypeId','placeholder' => 'тип объявления')))  
            ->add('category', 'entity', array('class' => 'DashboardCommonBundle:Category',
                                              'choice_label' => 'title',
                                              'required' => true, 'label' => 'Категория', 
                                              'group_by' => 'parent.title',
                                              'placeholder' => 'Нет',
                                              'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->where('c.parent IS NOT NULL');},
                                              'attr' => array('class' => 'form-control','placeholder' => 'Категория')))
            ->add('name', TextType::class, array('required' => true, 'label' => 'Название объявления', 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('required' => false, 'label' => 'Описание', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('price', TextType::class, array('required' => true, 'label' => 'Цена', 'attr' => array('class' => 'form-control')))
            ->add('mainfotoNew', FileType::class, array('required' => false, 'label' => '','mapped' => false, 'attr' => array('class' => 'form-control adv-foto-input ')))
            ->add('mainfoto', HiddenType::class, array('required' => false, 'label' => ''))
            ->add('fotos', CollectionType::class, array('entry_type' => new ProductFotoType($this->em), 'required' => false,'allow_add' => true,'allow_delete' => true, 'by_reference' => false))    
            ->add('viewcommon', CheckboxType::class, array('required' => false, 'label' => 'обычная продажа', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewcommon data-radio=view', 'text' => '<span>Обычная продажа</span>')))
            ->add('viewpremium', CheckboxType::class, array('required' => false, 'label' => 'премиум размещение', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewpremium data-radio=view', 'text' => '<span>Премиум размещение - 40 &euro;</span>')))
            ->add('viewselected', CheckboxType::class, array('required' => false, 'label' => 'выделить объявление', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewpremium data-radio=view', 'text' => '<span>Выделить объявление - 20 &euro;</span>')))
            ->add('metaTagTitle', TextType::class, array('required' => false, 'label' => 'Мета-тег Title', 'attr' => array('class' => 'form-control')))
            ->add('metaTagDescription', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Description', 'attr' => array('class' => 'form-control')))
            ->add('isConfirm',CheckboxType::class,array('required' => false, 'label' => 'Объявление одобрено') )
            ->add('isActive',CheckboxType::class,array('required' => false, 'label' => 'Объявление активно') )
            ->add('isBlocked',CheckboxType::class,array('required' => false, 'label' => 'Объявление заблокировано') )
            ->add('isCorrect',CheckboxType::class,array('required' => false, 'label' => 'Отправить на корректировку') )
            ->add('correctReason',TextareaType::class,array('required' => false, 'label' => 'Причина корректировки или блокировки', 'attr' => array('class' => 'form-control')) )                                          
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'form-main-button')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Product'
        ));
    }
    
    public function getName()
    {
        return 'product';
    }
}



