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

class RegionType extends AbstractType
{
    private $locale;
    private $region;
    private $city;
    
    public function __construct($locale, $region = null, $city = null) {
       $this->locale = $locale;
       $this->region = $region;
       $this->city = $city;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('region', 'entity', array('class' => 'DashboardCommonBundle:Region', 
                    'choice_label' => function($region)
                    {
                        if(count($region->getTranslations()) > 0)
                        {
                            foreach($region->getTranslations() as $translation)
                            {
                                if($translation->getLocale()->getId() == $this->locale->getId())
                                {
                                    return $translation->getValue();
                                }
                            }
                        }
                        else
                        {
                            return $region->getName();
                        }
                    }, 
                    'data' => $this->region,
                    'placeholder' => 'Atrašanās vieta', 
                    'required' => false,
                    'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('r')->orderBy('r.sortorder', 'ASC');},
                    'attr' => array('class' => 'custom-select')));
        
        $formModifier = function (FormInterface $form, Region $region = null) {
                $cities = null === $region ? array() : $region->getCity();

                $form->add('city', 'entity', array('class' => 'DashboardCommonBundle:City',
                                          'choice_label' => function($city)
                                          {
                                                if(count($city->getTranslations()) > 0)
                                                {
                                                    foreach($city->getTranslations() as $translation)
                                                    {
                                                        if($translation->getLocale()->getId() == $this->locale->getId())
                                                        {
                                                            return $translation->getValue();
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    return $city->getName();
                                                }
                                          },
                                          'data' => $this->city,
                                          'choices' => $cities,
                                          'placeholder' => 'Pilsēta/volost',
                                          'required' => false,
                                          'query_builder' => function(EntityRepository $er){
                                                return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                                          },
                                          'label' => 'Pilsēta/volost', 'attr' => array('class' => 'custom-select')));
            };
                           
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    if($this->region)
                    {
                        $formModifier($event->getForm(), $this->region);
                    }
                    else 
                    {
                        $data = $event->getData();
                        $formModifier($event->getForm(), $data->getRegion());
                    }
                }
            );
            
            $builder->get('region')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $region = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $region);
                }
            );
        
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Product',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'regionFilter';
    }
}

