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
    private $locale;
    private $city;
    
    public function __construct($locale, $city = null) {
       $this->locale = $locale;
       $this->city = $city;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'entity', array('class' => 'DashboardCommonBundle:City', 
                    'choice_label' => function($city)
                    {
                        return $city->getName();
                    }, 
                    'data' => $this->city,
                    'placeholder' => 'Выберите город', 
                    'required' => false,
                    'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('r')->orderBy('r.name', 'ASC');},
                    'attr' => array('class' => 'custom-select')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\City',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'regionFilter';
    }
}

