<?php
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Dashboard\CommonBundle\Entity\City;

class UserInfoType extends AbstractType
{
    public function __construct($em, $user, $locale) {
        $this->em = $em;
        $this->user = $user;
        $this->locale = $locale;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', 'entity', array('class' => 'DashboardCommonBundle:City', 
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
                    'placeholder' => 'Город',
                    'required' => false,
                    'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');},
                    'attr' => array('class' => 'custom-select')));
                    
         $formModifier = function (FormInterface $form, City $city = null) {
                $codes = null === $city ? array() : $city->getCodes();

                $form->add('cityCode', 'entity', array('class' => 'DashboardCommonBundle:CityCode',
                                          'choice_label' => 'code',
                                          'choices' => $codes,
                                          'required' => true,
                                          'placeholder' => 'Индекс',
                                          'label' => 'Pilsēta/volost', 'attr' => array('class' => 'custom-select','id' => 'cityCode','placeholder' => 'Индекс')));
            };
                           
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getCity());
                }
            );
            
            $builder->get('city')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $city = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $city);
                }
            );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\UserInfo',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'userinfo';
    }
}

