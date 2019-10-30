<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\Type\ModificationType;
use Dashboard\AdminBundle\Form\Type\GenerationBoardType;
use Dashboard\AdminBundle\Form\Type\GenerationItemType;
use Dashboard\AdminBundle\Form\DataTransformer\CategoryToNumberTransformer;

class GenerationType extends AbstractType
{
    private $manager;
    private $generations;
    
    public function __construct($manager, $generations) {
       $this->manager = $manager;
       $this->generations = $generations;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('required' => true,'label' => 'Название', 'attr' => array('class' => 'form-control')))
            ->add('yearFrom', TextType::class, array('required' => true,'label' => 'Год начала выпуска', 'attr' => array('class' => 'form-control')))
            ->add('yearTo', TextType::class, array('required' => true,'label' => 'Год окончания выпуска', 'attr' => array('class' => 'form-control')))
            ->add('translations', 'collection', array('type' => new TranslationType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('modifications', 'collection', array('type' => new ModificationType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
           ->add('boards', 'collection', array('type' => new GenerationBoardType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
           ->add('items', 'collection', array('type' => new GenerationItemType($this->manager, $this->generations), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))     
            ->add($builder->create('category', 'hidden')->addModelTransformer(new CategoryToNumberTransformer($this->manager)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Generation'
        ));
    }
    
    public function getName()
    {
        return 'generation';
    }
}

