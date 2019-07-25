<?php
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Doctrine\Common\Persistence\ObjectManager;
use Dashboard\CommonBundle\Form\DataTransformer\ConversationToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\UserToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

class ProfileMessageType extends AbstractType
{  
    public function __construct($em) {
       $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, array('required' => true, 'label' => 'Ziņojuma priekšmets: *', 'attr' => array('class' => 'form-control')))    
            ->add('message', TextareaType::class, array('required' => true, 'label' => 'Sludinājuma teksts: *', 'attr' => array('class' => 'send-message-textarea')))
            ->add($builder->create('userFrom', 'hidden')->addModelTransformer(new UserToNumberTransformer($this->em))) 
            ->add($builder->create('userTo', 'hidden')->addModelTransformer(new UserToNumberTransformer($this->em)))   
            ->add('save', ButtonType::class, array('label' => 'Sūtīt', 'attr' => array('class' => 'btn')));
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
        return 'profilemessage';
    }
}

