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
        $code = ($this->user && $this->user->getCityCode()) ? $this->user->getCityCode()->getCode() : '';
        $builder
            ->add('firstname', TextType::class, array('required' => true, 'label' => 'Имя', 'attr' => array('class' => 'form-control','placeholder' => 'Имя')))
            ->add('lastname', TextType::class, array('required' => false, 'label' => 'Фамилия', 'attr' => array('class' => 'form-control','placeholder' => 'Фамилия')))
            ->add('phone', TextType::class, array('required' => false, 'label' => 'Телефон', 'attr' => array('class' => 'form-control masked-phone','placeholder' => '+34')));
            if($this->user){
                $builder->add('avatarNew', FileType::class, array('required' => false, 'label' => '','mapped' => false, 'attr' => array('class' => 'change-avatar-input')))
                        ->add('avatar', HiddenType::class, array('required' => false, 'label' => ''))
                        ->add('region', 'entity', array('class' => 'DashboardCommonBundle:Region', 
                                          'choice_label' => 'name',
                                          'placeholder' => 'Регион',
                                          'required' => false,
                                          'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('r')->orderBy('r.name', 'ASC');},
                                          'attr' => array('class' => 'custom-select just-select', 'placeholder' => 'Регион', 'data-write' => '1')))
                        ->add('city', 'entity', array('class' => 'DashboardCommonBundle:City', 
                                          'choice_label' => 'name',
                                          'placeholder' => 'Город',
                                          'required' => false,
                                          'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');},
                                          'attr' => array('class' => 'custom-select just-select', 'placeholder' => 'Город', 'data-write' => '1')))
                        ->add('cityCode', TextType::class, array('required' => false, 'data' => $code,'mapped' => false, 'label' => 'Индекс', 'attr' => array('class' => 'form-control', 'placeholder' => 'Индекс', 'maxlength' => '5', 'autocomplete' => 'off')));
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

