<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Dashboard\CommonBundle\Entity\Region;

class CityType extends AbstractType
{
    private $region;
    
    public function __construct($region = null) {
       $this->region = $region;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'entity', array('class' => 'DashboardCommonBundle:Region', 
                                              'choice_label' => 'name', 
                                              'data' => $this->region,
                                              'required' => false,
                                              'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('r')->orderBy('r.name', 'ASC');},
                                              'attr' => array('class' => 'custom-select','placeholder' => 'Регион','data-write' => "1",'data-clearchange' => '1','onchange' => 'document.regionFilter.submit()')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Region',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'regionFilter';
    }
}

