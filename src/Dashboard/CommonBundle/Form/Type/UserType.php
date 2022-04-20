<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Dashboard\CommonBundle\Form\DataTransformer\InfoToNumberTransformer;
use Dashboard\CommonBundle\Form\Type\UserInfoType;
use Dashboard\CommonBundle\Form\Type\UserAlertsType;

class UserType extends AbstractType
{
    public function __construct($em, $user, $locale) {
        $this->em = $em;
        $this->user = $user;
        $this->locale = $locale;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('required' => false, 'label' => 'e-pasts', 'attr' => array('class' => 'form-control')))   
            ->add('alerts', CheckboxType::class, array('required' => false, 'label' => 'saņemt e-pasta paziņojumus', 'attr' => array('class' => 'custom-checkbox')))
            ->add('userinfo', new UserInfoType($this->em, $this->user, $this->locale), array('data_class' => 'Dashboard\CommonBundle\Entity\UserInfo'))
            ->add('save', ButtonType::class, array('label' => 'Saglabājiet', 'attr' => array('class' => 'message-button-answer')));
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
        return 'userMain';
    }
}
    

