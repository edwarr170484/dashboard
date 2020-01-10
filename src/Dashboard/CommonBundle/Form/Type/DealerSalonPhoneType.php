<?php
namespace Dashboard\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Dashboard\CommonBundle\Form\DataTransformer\DealerSalonToNumberTransformer;

class DealerSalonPhoneType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('phone', TextType::class, array('required' => true, 'label' => '', 'attr' => array('class' => 'form-control masked-phone','placeholder' => '+34')))
            ->add($builder->create('dealerSalon', 'hidden')->addModelTransformer(new DealerSalonToNumberTransformer($this->manager)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\DealerSalonPhone',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'dealerSalonPhone';
    }
}
