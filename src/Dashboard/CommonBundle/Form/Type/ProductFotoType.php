<?php
namespace Dashboard\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

class ProductFotoType extends AbstractType
{
    private $em;
    
    public function __construct($em) {
       $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('foto', HiddenType::class, array('required' => false, 'label' => ''))
            ->add('sortorder', HiddenType::class, array('required' => false, 'label' => ''))
            ->add('fotoNew', FileType::class, array('required' => false, 'mapped' => false, 'label' => ''))
            ->add($builder->create('product', 'hidden')->addModelTransformer(new ProductToNumberTransformer($this->em)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\ProductFotos',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'foto';
    }
}
