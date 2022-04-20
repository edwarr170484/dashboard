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

use Doctrine\Common\Persistence\ObjectManager;
use Dashboard\CommonBundle\Form\DataTransformer\InfoToNumberTransformer;

use Dashboard\CommonBundle\Entity\Region;

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
            ->add('midname', TextType::class, array('required' => false, 'label' => 'patronimisks', 'attr' => array('class' => 'form-control','placeholder' => 'Отчество')))
            ->add('phone', TextType::class, array('required' => false, 'label' => 'Tālruņa numurs', 'attr' => array('class' => 'form-control','placeholder' => 'Телефон')))
            ->add('avatarNew', FileType::class, array('required' => false, 'label' => '','mapped' => false, 'attr' => array('class' => 'change-avatar-input form-control')))
            ->add('avatar', HiddenType::class, array('required' => false, 'label' => ''))
            ->add('sex', ChoiceType::class,array('choices' => array('male' => 'Vīrietis sieviete', 'female' => 'Sieviešu'),
                                                 'label' => 'dzimums',
                                                 'required' => false,
                                                 'placeholder' => 'Sekss',
                                                 'attr' => array('class' => 'custom-select')))
            ->add('birthdayday', ChoiceType::class, array('choices'  => array(
                '1' => '1','2' => '2','3' => '3',
                '4' => '4','5' => '5','6' => '6',
                '7' => '7','8' => '8','9' => '9',
                '10' => '10','11' => '11', '12' => '12',
                '13' => '13','14' => '14', '15' => '15',
                '16' => '16','17' => '17', '18' => '18',
                '19' => '19','20' => '20', '21' => '21',
                '22' => '22', '23' => '23', '24' => '24',
                '25' => '25', '26' => '26', '27' => '27',
                '28' => '28', '29' => '29', '30' => '30',
                '31' => '31'),'placeholder' => 'Diena','required' => false, 'attr' => array('class' => 'custom-select')))
            ->add('birthdaymonth', ChoiceType::class, array('choices'  => array(
                '1' => 'Janvāris','2' => 'Februāris','3' => 'Marts',
                '4' => 'Aprīlis','5' => 'Maijs','6' => 'Jūnijs',
                '7' => 'Jūlijs','8' => 'Augusts','9' => 'Septembris',
                '10' => 'Oktobris','11' => 'Novembris', '12' => 'Decembris')
                ,'required' => false, 'placeholder' => 'Mēnesis', 'attr' => array('class' => 'custom-select','placeholder' => 'Mēnesis')))
            ->add('birthdayyear', ChoiceType::class, array('choices'  => $this->array_create(2003, 1940),'placeholder' => 'Gads','required' => false, 'attr' => array('class' => 'custom-select')))
            ->add('region', 'entity', array('class' => 'DashboardCommonBundle:Region', 
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
                    'label' => 'Atrašanās vieta', 
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
                                          'choices' => $cities,
                                          'required' => false,
                                          'placeholder' => 'Pilsēta/volost',
                                          'query_builder' => function(EntityRepository $er){
                                                return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                                          },
                                          'label' => 'Pilsēta/volost', 'attr' => array('class' => 'custom-select','id' => 'city','placeholder' => 'Pilsēta/volost')));
            };
                           
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getRegion());
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
            'data_class' => 'Dashboard\CommonBundle\Entity\UserInfo',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'userinfo';
    }
    
    private function array_create($start_num,$stop_num)
    {
        $nums = array();
        
        for($i = 0;$i <= ($start_num - $stop_num);$i++)
        {
            $nums[$start_num + $i] = $start_num - $i;
        }
        
        return $nums;
    }
}

