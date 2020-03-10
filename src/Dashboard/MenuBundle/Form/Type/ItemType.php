<?php

namespace Dashboard\MenuBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Dashboard\MenuBundle\Form\DataTransformer\PageToNumberTransformer;
use Dashboard\MenuBundle\Form\DataTransformer\MenuToNumberTransformer;
use Dashboard\MenuBundle\Form\DataTransformer\MenuItemsToNumberTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

class ItemType extends AbstractType
{    
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('title', 'text', array('required' => true, 'label' => 'Название пункта меню', 'attr' => array('class' => 'form-control')))
            ->add('parent', 'entity', array('class' => 'DashboardMenuBundle:MenuItem',
                            'choice_label' => 'title',
                            'empty_data' => null,
                            'required' => false,
                            'label' => 'Родитель', 'attr' => array('class' => 'form-control','placeholder' => 'Родитель')))
            ->add('category', 'entity', array('class' => 'DashboardCommonBundle:Category',
                            'choice_label' => 'title',
                            'empty_data' => null,
                            'required' => false,
                            'label' => 'Родитель', 'attr' => array('class' => 'form-control','placeholder' => 'Родитель')))
            ->add('link', 'text', array('required' => false, 'label' => 'Ссылка', 'attr' => array('class' => 'form-control')))
            ->add('page', 'entity', array('class' => 'DashboardCommonBundle:Page',
                            'choice_label' => 'title',
                            'empty_data' => null,
                            'required' => false,
                            'group_by' => 'locale.name',
                            'label' => 'Страница', 'attr' => array('class' => 'form-control','placeholder' => 'Страница')))
            ->add($builder->create('menu', 'hidden')->addModelTransformer(new MenuToNumberTransformer($this->manager)))    
            ->add('sortorder', 'text', array('required' => false, 'label' => 'Порядок', 'attr' => array('class' => 'item-sortorder')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\MenuBundle\Entity\MenuItem',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'item';
    }
    
}


