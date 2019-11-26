<?php
namespace Dashboard\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Dashboard\CommonBundle\Form\DataTransformer\DealerInfoToNumberTransformer;

class DealerPhoneType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('phone', TextType::class, array('required' => true, 'label' => '', 'attr' => array('class' => 'form-control','placeholder' => '+34')))
            ->add($builder->create('dealerInfo', 'hidden')->addModelTransformer(new DealerInfoToNumberTransformer($this->manager)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\DealerPhone',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'dealerPhone';
    }
}
