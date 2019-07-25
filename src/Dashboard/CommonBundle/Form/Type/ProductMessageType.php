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
            ->add('userName', TextType::class, array('required' => true, 'data' => $this->user->getUserinfo()->getFirstname() . " " . $this->user->getUserinfo()->getLastname(),'mapped' => false,'label' => 'Ваше имя:*', 'attr' => array('class' => 'form-control')))     
            ->add('userEmail', TextType::class, array('required' => true, 'data' => $this->user->getEmail(),'mapped' => false,'label' => 'Jūsu vārds: *', 'attr' => array('class' => 'form-control')))
            ->add('userPhone', TextType::class, array('required' => true, 'data' => $this->user->getUserinfo()->getPhone() ,'mapped' => false,'label' => 'Jūsu e-pasts'.': *', 'attr' => array('class' => 'form-control')))
            ->add('message', TextareaType::class, array('required' => true, 'label' => 'Sludinājuma teksts: *', 'attr' => array('class' => 'form-control')))
            ->add('copytoemail', CheckboxType::class, array('mapped' => false, 'required' => false,'label' => 'Sūtiet man vēstules kopiju', 'attr' => array('class' => 'hidden-input', 'id' => 'copytoemail')) )
            ->add('save', ButtonType::class, array('label' => 'SŪTĪT', 'attr' => array('class' => 'send-tab-form')));
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

