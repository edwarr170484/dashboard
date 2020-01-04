<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Dashboard\CommonBundle\Form\Type\ProductFotoType;
use Dashboard\AdminBundle\Form\Type\ProductInfoType;
use Dashboard\CommonBundle\Form\DataTransformer\CategoryToNumberTransformer;

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
            ->add('authorEmail', EmailType::class, array('required' => true, 'data' => $this->user->getUser()->getEmail() ,'label' => 'Ваш e-mail', 'attr' => array('class' => 'form-control')))
            ->add('authorPhone', TextType::class, array('required' => true, 'label' => 'Контактный телефон ', 'attr' => array('class' => 'form-control')))
            ->add('region', 'entity', array('class' => 'DashboardCommonBundle:Region','choice_label' => 'name','required' => false, 'label' => 'Страна', 'attr' => array('class' => 'form-control')))
            ->add('city', 'entity', array('class' => 'DashboardCommonBundle:City','choice_label' => 'name','required' => true, 'label' => 'Город', 'attr' => array('class' => 'form-control')))
            ->add('cityCode', 'entity', array('class' => 'DashboardCommonBundle:CityCode','choice_label' => 'code','required' => true, 'label' => 'Индекс', 'attr' => array('class' => 'form-control')))
            ->add('baseCategory', 'entity', array('class' => 'DashboardCommonBundle:Category',
                                              'choice_label' => 'title',
                                              'required' => true, 
                                              'label' => 'Базовая категория', 
                                              'placeholder' => 'Нет',
                                              'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->where('c.parent IS NULL');},
                                              'attr' => array('class' => 'form-control','placeholder' => 'Категория')))
            ->add('category', 'entity', array('class' => 'DashboardCommonBundle:Category',
                                              'choice_label' => 'title',
                                              'required' => true, 
                                              'label' => 'Модель', 
                                              'group_by' => 'parent.title',
                                              'placeholder' => 'Нет',
                                              'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->where('c.parent IS NOT NULL');},
                                              'attr' => array('class' => 'form-control','placeholder' => 'Категория')))
            ->add('name', TextType::class, array('required' => true, 'label' => 'Название объявления', 'attr' => array('class' => 'form-control')))
            ->add('fotos', CollectionType::class, array('entry_type' => new ProductFotoType($this->em), 'required' => false,'allow_add' => true,'allow_delete' => true, 'by_reference' => false))
            ->add('info', new ProductInfoType($this->em), array('data_class' => 'Dashboard\CommonBundle\Entity\ProductInfo'))                                          
            ->add('isConfirm',CheckboxType::class,array('required' => false, 'label' => 'Объявление одобрено'))
            ->add('isActive',CheckboxType::class,array('required' => false, 'label' => 'Объявление активно'))
            ->add('isBlocked',CheckboxType::class,array('required' => false, 'label' => 'Объявление заблокировано'))
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



