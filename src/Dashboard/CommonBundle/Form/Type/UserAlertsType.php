<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class UserAlertsType extends AbstractType
{
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('emailmessagesalerts', CheckboxType::class, array('required' => false, 'label' => 'paziņojums', 'attr' => array('class' => 'hidden-input', 'id' => 'messages')))
            ->add('emailmessagesreminders', CheckboxType::class, array('required' => false, 'label' => 'paziņojums', 'attr' => array('class' => 'hidden-input', 'id' => 'reminders')))    
            ->add('save', ButtonType::class, array('label' => 'Saglabājiet', 'attr' => array('class' => 'message-button-answer')));
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
        return 'userMessagesSettings';
    }
}

