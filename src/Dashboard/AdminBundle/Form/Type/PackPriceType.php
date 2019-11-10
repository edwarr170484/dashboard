<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Dashboard\AdminBundle\Form\DataTransformer\PackToNumberTransformer;

class PackPriceType extends AbstractType
{   
    private $manager;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('billId', TextType::class, array('required' => true,'label' => 'ID услуги для счет-фактуры', 'attr' => array('class' => 'form-control')))
            ->add('category', 'entity', array('class' => 'DashboardCommonBundle:Category',
                            'choice_label' => 'title',
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->where('c.parent IS NULL');},
                            'label' => 'Локализация:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => 'Локализация:')))
            ->add('price', TextType::class, array('required' => true,'label' => 'Цена', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('pack', 'hidden')->addModelTransformer(new PackToNumberTransformer($this->manager)));    
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\PackPrice'
        ));
    }
    
    public function getName()
    {
        return 'packprice';
    }
}




