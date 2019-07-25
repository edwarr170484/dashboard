<?php
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Doctrine\Common\Persistence\ObjectManager;
use Dashboard\CommonBundle\Form\DataTransformer\ConversationToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\UserToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

class MessageType extends AbstractType
{  
    public function __construct($em, $user) {
       $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, array('required' => true, 'label' => 'ziņa', 'attr' => array('class' => 'send-message-textarea')))
            ->add('image', FileType::class, array('required' => false, 'label' => '','mapped' => false))
            ->add($builder->create('product', 'hidden')->addModelTransformer(new ProductToNumberTransformer($this->em)))   
            ->add('save', ButtonType::class, array('label' => 'Sūtīt', 'attr' => array('class' => 'message-button-answer')));
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
        return 'usermessage';
    }
}

