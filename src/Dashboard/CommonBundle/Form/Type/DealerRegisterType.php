<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Dashboard\CommonBundle\Form\Type\DealerInfoType;
use Dashboard\CommonBundle\Form\Type\UserInfoType;

class DealerRegisterType extends AbstractType
{
    public function __construct($manager, $user, $locale) {
        $this->manager = $manager;
        $this->user = $user;
        $this->locale = $locale;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder 
            ->add('email', EmailType::class, array('required' => true, 'label' => 'E-mail', 'attr' => array()))
            ->add('password', RepeatedType::class, array(
                    'required' => true,
                    'type' => PasswordType::class,
                    'first_options'  => array('label' => 'Пароль', 'attr' => array('class' => 'password')),
                    'second_options' => array('label' => 'Повторить пароль', 'attr' => array('class' => 'password')),
                    'attr' => array('class' => 'password','placeholder' => 'Пароль')
                )
            )
            ->add('userinfo', new UserInfoType($this->manager, $this->user, $this->locale), array('data_class' => 'Dashboard\CommonBundle\Entity\UserInfo'))
            ->add('dealerinfo', new DealerInfoType($this->manager, $this->user, $this->locale), array('data_class' => 'Dashboard\CommonBundle\Entity\DealerInfo'));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\User',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'dealerRegister';
    }
}

