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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
            ->add('firstname', TextType::class, array('required' => true, 'label' => 'vārds', 'attr' => array('class' => 'form-control','placeholder' => 'Имя')))
            ->add('lastname', TextType::class, array('required' => false, 'label' => 'uzvārds', 'attr' => array('class' => 'form-control','placeholder' => 'Фамилия')))
            ->add('phone', TextType::class, array('required' => false, 'label' => 'Телефон', 'attr' => array('class' => 'form-control','placeholder' => 'Телефон')));
            if($this->user){
               $builder->add('avatarNew', FileType::class, array('required' => false, 'label' => '','mapped' => false, 'attr' => array('class' => 'change-avatar-input')))
                       ->add('avatar', HiddenType::class, array('required' => false, 'label' => ''));
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

