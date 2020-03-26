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
use Dashboard\CommonBundle\Entity\Region;

class DealerInfoType extends AbstractType
{
    public function __construct($em, $user, $locale) {
        $this->em = $em;
        $this->user = $user;
        $this->locale = $locale;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $code = ($this->user && $this->user->getDealerInfo()->getCityCode()) ? $this->user->getDealerInfo()->getCityCode()->getCode() : null;
        $builder
            ->add('company', TextType::class, array('required' => true, 'label' => 'Компания', 'attr' => array('class' => 'form-control')))
            ->add('nifNumber', TextType::class, array('required' => true, 'label' => 'N.I.F. / C.I.F.', 'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array('required' => false, 'label' => 'Адрес', 'attr' => array('class' => 'form-control')))
            ->add('website', TextType::class, array('required' => false, 'label' => 'Сайт', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('user', 'hidden')->addModelTransformer(new UserToNumberTransformer($this->em)))
            ->add('region', 'entity', array('class' => 'DashboardCommonBundle:Region', 
                                        'choice_label' => 'name',
                                        'placeholder' => 'Регион',
                                        'required' => false,
                                        'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('r')->orderBy('r.name', 'ASC');},
                                        'attr' => array('class' => 'custom-select just-select', 'placeholder' => 'Регион', 'data-write' => '1')));
            if($code){
                $builder->add('cityCode', TextType::class, array('required' => false, 'data' => $code, 'mapped' => false, 'label' => 'Индекс', 'attr' => array('class' => 'form-control', 'placeholder' => 'Индекс', 'maxlength' => '5', 'autocomplete' => 'off')));
            }else{
                $builder->add('cityCode', TextType::class, array('required' => false, 'mapped' => false, 'label' => 'Индекс', 'attr' => array('class' => 'form-control', 'placeholder' => 'Индекс', 'maxlength' => '5', 'autocomplete' => 'off')));
            }                                    
            
            if($this->user){
                $builder->add('avatarNew', FileType::class, array('required' => false, 'label' => '','mapped' => false, 'attr' => array('class' => 'change-avatar-input form-control')))
                        ->add('avatar', HiddenType::class, array('required' => false, 'label' => ''));
            }
            
            $formModifier = function (FormInterface $form, Region $region = null) {
                    $cities = null === $region ? array() : $region->getCity();
                    $form->add('city', 'entity', array('class' => 'DashboardCommonBundle:City',
                                        'choice_label' => 'name',
                                        'choices' => $cities,
                                        'required' => false,
                                        'placeholder' => 'Город',
                                        'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');},
                                        'label' => 'Город', 'attr' => array('class' => 'custom-select just-select', 'placeholder' => 'Город', 'data-write' => '1')));
            };
            
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), ($data) ? $data->getRegion() : null);
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
            'data_class' => 'Dashboard\CommonBundle\Entity\DealerInfo',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'dealerinfo';
    }
}

