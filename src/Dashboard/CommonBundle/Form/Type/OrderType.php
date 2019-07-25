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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Form\Type\ProductFotoType;
use Dashboard\CommonBundle\Entity\Selltype;

use Dashboard\CommonBundle\Form\DataTransformer\CategoryToNumberTransformer;

use Dashboard\CommonBundle\Entity\Category;

class OrderType extends AbstractType
{
    public function __construct($em, $currentuser, $productuser) {
        $this->em = $em;
        $this->currentuser = $currentuser;
        $this->productuser = $productuser;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('name', TextType::class, array('required' => true, 'data' => $this->currentuser->getFirstname(),'label' => 'Jūsu vārds: *', 'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array('required' => true, 'data' => $this->currentuser->getUser()->getEmail() ,'label' => 'Jūsu e-pasts: *', 'attr' => array('class' => 'form-control')))
            ->add('phone', TextType::class, array('required' => true, 'data' => $this->currentuser->getPhone() ,'label' => 'Kontakt tālrunis: *', 'attr' => array('class' => 'form-control')))
            ->add('comment', TextareaType::class, array('required' => false, 'label' => 'Komentēt pasūtījumu', 'attr' => array('class' => 'form-control')))
            ->add('save', ButtonType::class, array('label' => 'Sūtīt pasūtījumu', 'attr' => array('class' => 'btn')));
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\ProductOrder',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'order';
    }
}

