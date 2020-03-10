<?php
   
namespace Dashboard\MenuBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class MenuType extends AbstractType
{
    private $em;
    
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('required' => true, 'label' => 'Название меню', 'attr' => array('class' => 'form-control')))
            ->add('name', 'text', array('required' => true, 'label' => 'Имя меню (транслит)', 'attr' => array('class' => 'form-control')))
            ->add('position', 'choice', array('choices' => array('top'   => 'Вверху','bottom' => 'Внизу','left'   => 'Слева','right' => 'Справа'), 'label' => 'Расположение', 'attr' => array('class' => 'form-control')))   
            ->add('sort', 'text', array('required' => false, 'label' => 'Порядок', 'attr' => array('class' => 'form-control')))
            ->add('locale', 'entity', array('class' => 'DashboardCommonBundle:Locale',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('l')->orderBy('l.sortorder', 'ASC');},
                            'label' => 'Локализация:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => 'Локализация:')))
            ->add('exit', HiddenType::class, array('required' => false, 'data' => '0', 'mapped' => false))
            ->add('items', 'collection', array('type' => new ItemType($this->em), 'allow_add'    => true, 'allow_delete' => true, 'by_reference' => false));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\MenuBundle\Entity\Menu',
        ));
    }
    
    public function getName()
    {
        return 'menu';
    }
}
