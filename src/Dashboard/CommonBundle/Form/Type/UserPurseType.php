<?php
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Doctrine\Common\Persistence\ObjectManager;
use Dashboard\CommonBundle\Form\DataTransformer\InfoToNumberTransformer;


class UserPurseType extends AbstractType
{
    public function __construct($em, $user) {
        $this->em = $em;
        $this->user = $user;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('balanse', TextType::class, array('required' => false, 'label' => 'Konta atlikums', 'attr' => array('class' => 'form-control','placeholder' => 'Баланс')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\UserPurse',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'userpurse';
    }
}



