<?php
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Doctrine\Common\Persistence\ObjectManager;
use Dashboard\CommonBundle\Form\DataTransformer\ConversationToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\UserToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

class ProductMessageType extends AbstractType
{  
    public function __construct($manager, $user) {
       $this->manager = $manager;
       $this->user = $user;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName', TextType::class, array('required' => false, 'data' => $this->user->getUserinfo()->getFirstname() . " " . $this->user->getUserinfo()->getLastname(),'mapped' => false,'label' => 'Ваше имя:*', 'attr' => array('class' => 'form-control', 'placeholder' => 'Ваше имя *')))     
            ->add('userEmail', TextType::class, array('required' => false, 'data' => $this->user->getEmail(),'mapped' => false,'label' => 'E-mail: *', 'attr' => array('class' => 'form-control', 'placeholder' => 'E-mail *')))
            ->add('userPhone', TextType::class, array('required' => false, 'data' => $this->user->getUserinfo()->getPhone() ,'mapped' => false,'label' => ''.': *', 'attr' => array('class' => 'form-control', 'placeholder' => 'Телефон')))
            ->add('message', TextareaType::class, array('required' => true, 'label' => 'Текст сообщения: *', 'attr' => array('class' => 'form-control', 'placeholder' => 'Текст сообщения *')))
            ->add('save', ButtonType::class, array('label' => 'Отправить', 'attr' => array('class' => 'send-tab-form')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Message',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'productmessage';
    }
}

