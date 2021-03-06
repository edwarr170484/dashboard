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
    private $user;
    public function __construct($user) {
        $this->user = $user;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->user){
            $builder
                ->add('name', TextType::class, array('required' => true, 'data' => $this->user->getUserinfo()->getFirstname() . ' ' . $this->user->getUserinfo()->getLastname(), 'label' => 'Имя: *', 'attr' => array('class' => 'form-control', 'placeholder' => 'Имя *')))
                ->add('email', EmailType::class, array('required' => true, 'data' => $this->user->getEmail(),'label' => 'Email: *', 'attr' => array('class' => 'form-control', 'placeholder' => 'Email *')))
                ->add('phone', TextType::class, array('required' => false,'data' => $this->user->getUserinfo()->getPhone(), 'label' => 'Телефон:', 'attr' => array('class' => 'form-control masked-phone', 'placeholder' => 'Телефон')))
                ->add('comment', TextareaType::class, array('required' => false, 'label' => 'Комментарий', 'attr' => array('class' => 'form-control', 'placeholder' => 'Комментарий')))
                ->add('save', ButtonType::class, array('label' => 'Оставить заявку', 'attr' => array('class' => 'btn')));
        }else{
            $builder
                ->add('name', TextType::class, array('required' => true, 'label' => 'Имя: *', 'attr' => array('class' => 'form-control', 'placeholder' => 'Имя *')))
                ->add('email', EmailType::class, array('required' => true, 'label' => 'Email: *', 'attr' => array('class' => 'form-control', 'placeholder' => 'Email *')))
                ->add('phone', TextType::class, array('required' => false, 'label' => 'Телефон:', 'attr' => array('class' => 'form-control masked-phone', 'placeholder' => 'Телефон')))
                ->add('comment', TextareaType::class, array('required' => false, 'label' => 'Комментарий', 'attr' => array('class' => 'form-control', 'placeholder' => 'Комментарий')))
                ->add('save', ButtonType::class, array('label' => 'Оставить заявку', 'attr' => array('class' => 'btn')));
        }
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

