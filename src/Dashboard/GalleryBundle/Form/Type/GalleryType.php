<?php
   
namespace Dashboard\GalleryBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class GalleryType extends AbstractType
{
    private $em;
    
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => true, 'label' => 'Название', 'attr' => array('class' => 'form-control')))
            ->add('translit', 'text', array('required' => true, 'label' => 'Транслит', 'attr' => array('class' => 'form-control')))
            ->add('description', 'textarea', array('required' => true, 'label' => 'Описание', 'attr' => array('class' => 'form-control')))
            ->add('sort', 'text', array('required' => false, 'label' => 'Порядок', 'attr' => array('class' => 'form-control')))
            ->add('items', 'collection', array('type' => new ItemType($this->em), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false,
                                               'attr' => array('class' => 'gallery_images')))
            ->add('locale', 'entity', array('class' => 'DashboardCommonBundle:Locale',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('l')->orderBy('l.sortorder', 'ASC');},
                            'label' => 'Локализация:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => 'Локализация:')))
            ->add('save', 'submit', array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\GalleryBundle\Entity\Gallery',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'gallery';
    }
}
