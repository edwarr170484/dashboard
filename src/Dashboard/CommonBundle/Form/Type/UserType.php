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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Form\Type\UserInfoType;

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
            ->add('firstname', TextType::class, array('required' => true, 'label' => 'vārds', 'attr' => array('class' => 'form-control','placeholder' => 'Имя')))
            ->add('lastname', TextType::class, array('required' => false, 'label' => 'uzvārds', 'attr' => array('class' => 'form-control','placeholder' => 'Фамилия')))
            ->add('phone', TextType::class, array('required' => false, 'label' => 'Телефон', 'attr' => array('class' => 'form-control','placeholder' => 'Телефон')))
            ->add('avatarNew', FileType::class, array('required' => false, 'label' => '','mapped' => false, 'attr' => array('class' => 'change-avatar-input')))
            ->add('avatar', HiddenType::class, array('required' => false, 'label' => ''))
            ->add('isHideEmail', CheckboxType::class, array('required' => false, 'label' => 'Запретить показ на сайте', 'attr' => array('class' => 'custom-checkbox')))
            ->add('userinfo', new UserInfoType($this->em, $this->user, $this->locale), array('data_class' => 'Dashboard\CommonBundle\Entity\UserInfo'))
            ->add('save', ButtonType::class, array('label' => 'Сохранить'));
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
    

