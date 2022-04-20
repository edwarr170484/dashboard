<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Dashboard\CommonBundle\Form\DataTransformer\InfoToNumberTransformer;
use Dashboard\CommonBundle\Form\Type\UserInfoType;


class UserPasswordType extends AbstractType
{
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('passwordNew', PasswordType::class, array('required' => true, 'data' => '','mapped' => false,'label' => 'Jauna parole', 'attr' => array('class' => 'form-control')))
            ->add('passwordConfirm', PasswordType::class, array('required' => true, 'data' => '','mapped' => false,'label' => 'Apstipriniet paroli', 'attr' => array('class' => 'form-control'))) 
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
        return 'userPassword';
    }
}

