<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Dashboard\CommonBundle\Form\DataTransformer\CategoryToNumberTransformer;

class DescriptionType extends AbstractType
{
    private $em;
    
    public function __construct($em) {
       $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('locale', 'entity', array('class' => 'DashboardCommonBundle:Locale',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('l')->orderBy('l.sortorder', 'ASC');},
                            'label' => 'Локализация:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => 'Локализация:')))
            ->add('description', TextareaType::class, array('required' => false, 'label' => 'Значение для перевода', 'attr' => array('class' => 'form-control','placeholder' => 'Значение для перевода')))
            ->add($builder->create('category', 'hidden')->addModelTransformer(new CategoryToNumberTransformer($this->em)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\CategoryDescription'
        ));
    }
    
    public function getName()
    {
        return 'categoryDescription';
    }
}

