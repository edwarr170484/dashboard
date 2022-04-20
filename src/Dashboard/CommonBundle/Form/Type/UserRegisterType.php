<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Form\DataTransformer\InfoToNumberTransformer;
use Dashboard\CommonBundle\Form\Type\UserInfoType;
use Dashboard\CommonBundle\Form\Type\UserAlertsType;

class UserRegisterType extends AbstractType
{
    
    public function __construct($link) {
        $this->link = $link;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('email', EmailType::class, array('required' => true, 'label' => 'e-pasts', 'attr' => array('placeholder' => 'email')))
            ->add('link', HiddenType::class, array('required' => false,'data' => $this->link,'mapped' => false))
            ->add('password', RepeatedType::class, array(
                    'required' => true,
                    'type' => PasswordType::class,
                    'first_options'  => array('label' => 'Password', 'attr' => array('placeholder' => 'parole')),
                    'second_options' => array('label' => 'Repeat Password', 'attr' => array('placeholder' => 'apstipriniet paroli')),
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('alerts', CheckboxType::class,  array('required' => false, 'label' => 'pieņemt paziņojumus no vietnes', 'attr' => array('class' => 'hidden-input', 'id' => 'alerts','text' => 'pieņemt paziņojumus no vietnes')))
            ->add('termsAccept', CheckboxType::class, array(
                'mapped' => false,
                'required' => false,
                'data' => true,
                'label' => 'es piekrītu <a href="" data-toggle="modal" data-target="#userAgreementModal"> lietotāja līgumam </a>',
                'attr' =>  array('class' => 'hidden-input', 'id' => 'termsAccept', 
                'text' => 'es piekrītu <a href="" data-toggle="modal" data-target="#userAgreementModal"> lietotāja līgumam </a>')
            ));
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
        return 'userRegister';
    }
}
    



