<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\DataTransformer\GenerationToNumberTransformer;

class ModificationType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('power', TextType::class, array('required' => true,'label' => 'Мощность двигателя', 'attr' => array('class' => 'form-control')))
            ->add('size', TextType::class, array('required' => true,'label' => 'Объем двигателя', 'attr' => array('class' => 'form-control')))
            ->add('label', TextType::class, array('required' => true,'label' => 'Надпись для года', 'attr' => array('class' => 'form-control')))
            ->add('sortorder', TextType::class, array('required' => false,'label' => 'Порядок', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('generation', 'hidden')->addModelTransformer(new GenerationToNumberTransformer($this->manager)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Modification'
        ));
    }
    
    public function getName()
    {
        return 'Modification';
    }
}
