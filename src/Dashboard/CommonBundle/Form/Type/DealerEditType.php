<?php
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Entity\City;
use Dashboard\CommonBundle\Form\DataTransformer\UserToNumberTransformer;

class DealerEditType extends AbstractType
{
    public function __construct($em, $locale) {
        $this->em = $em;
        $this->locale = $locale;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', TextType::class, array('required' => true, 'label' => 'Компания', 'attr' => array('class' => 'form-control')))
            ->add('firma', TextType::class, array('required' => true, 'label' => 'Фирма', 'attr' => array('class' => 'form-control')))
            ->add('nifNumber', TextType::class, array('required' => true, 'label' => 'N.I.F. / C.I.F.', 'attr' => array('class' => 'form-control')))
            ->add('website', TextType::class, array('required' => false, 'label' => 'Сайт', 'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array('required' => true, 'label' => 'Адрес', 'attr' => array('class' => 'form-control')))
            ->add('email', TextType::class, array('required' => true, 'label' => 'Email', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('user', 'hidden')->addModelTransformer(new UserToNumberTransformer($this->em)))
            ->add('logotypeNew', FileType::class, array('required' => false, 'label' => 'Логотип','mapped' => false, 'attr' => array('class' => 'change-avatar-input')))
            ->add('logotype', HiddenType::class, array('required' => false, 'label' => ''));
            
            $builder->add('city', 'entity', array('class' => 'DashboardCommonBundle:City', 
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
                    'label' => 'Город',
                    'required' => true,
                    'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');},
                    'attr' => array('class' => 'custom-select')));
                    
         $formModifier = function (FormInterface $form, City $city = null) {
                $codes = null === $city ? array() : $city->getCodes();

                $form->add('cityCode', 'entity', array('class' => 'DashboardCommonBundle:CityCode',
                                          'choice_label' => 'code',
                                          'choices' => $codes,
                                          'required' => false,
                                          'placeholder' => 'Индекс',
                                          'label' => 'Индекс', 'attr' => array('class' => 'custom-select','id' => 'cityCode')));
            };
                           
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), ($data) ? $data->getCity() : NULL);
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
            'data_class' => 'Dashboard\CommonBundle\Entity\DealerInfo',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'dealerinfo';
    }
}

