<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Dashboard\CommonBundle\Form\DataTransformer\CategoryToNumberTransformer;
use Dashboard\AdminBundle\Form\DataTransformer\FilterToNumberTransformer;
use Dashboard\AdminBundle\Form\DataTransformer\FilterValueToNumberTransformer;
use Dashboard\AdminBundle\Form\DataTransformer\RegionToNumberTransformer;
use Dashboard\AdminBundle\Form\DataTransformer\CityToNumberTransformer;
use Dashboard\AdminBundle\Form\DataTransformer\ServiceToNumberTransformer;
use Dashboard\AdminBundle\Form\DataTransformer\GenerationToNumberTransformer;
use Dashboard\AdminBundle\Form\DataTransformer\PackToNumberTransformer;
use Dashboard\AdminBundle\Form\DataTransformer\RateToNumberTransformer;

class TranslationType extends AbstractType
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
            ->add('value', TextareaType::class, array('required' => false, 'label' => 'Значение для перевода', 'attr' => array('class' => 'form-control','placeholder' => 'Значение для перевода')))
            ->add($builder->create('category', 'hidden')->addModelTransformer(new CategoryToNumberTransformer($this->em)))
            ->add($builder->create('filter', 'hidden')->addModelTransformer(new FilterToNumberTransformer($this->em)))                        
            ->add($builder->create('filterValue', 'hidden')->addModelTransformer(new FilterValueToNumberTransformer($this->em)))
            ->add($builder->create('region', 'hidden')->addModelTransformer(new RegionToNumberTransformer($this->em)))                        
            ->add($builder->create('city', 'hidden')->addModelTransformer(new CityToNumberTransformer($this->em)))
            ->add($builder->create('service', 'hidden')->addModelTransformer(new ServiceToNumberTransformer($this->em)))
            ->add($builder->create('generation', 'hidden')->addModelTransformer(new GenerationToNumberTransformer($this->em)))
            ->add($builder->create('pack', 'hidden')->addModelTransformer(new PackToNumberTransformer($this->em)))
            ->add($builder->create('rate', 'hidden')->addModelTransformer(new RateToNumberTransformer($this->em)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Translation'
        ));
    }
    
    public function getName()
    {
        return 'translation';
    }
}

