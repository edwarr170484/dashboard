<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\DataTransformer\GenerationToNumberTransformer;

class GenerationBoardType extends AbstractType
{
    private $manager;
    
    public function __construct($manager) {
       $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('imageNew', 'file', array('required' => false, 'label' => 'Изображение','mapped' => false, 'attr' => array('class' => 'form-control')))
            ->add('image', 'hidden', array('required' => true, 'label' => ''))
            ->add('board', 'entity', array('class' => 'DashboardCommonBundle:FilterValue',
                           'choice_label' => 'value',
                           'empty_data' => null,
                           'required' => true, 
                           'query_builder' => function(EntityRepository $er){
                                $filter = $this->manager->getRepository("DashboardCommonBundle:Filter")->find(19);
                                return $er->createQueryBuilder('v')->where('v.filter = :filter')->setParameter('filter',$filter);   
                           },
                           'label' => 'Тип кузова:', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('generation', 'hidden')->addModelTransformer(new GenerationToNumberTransformer($this->manager)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\GenerationBoard'
        ));
    }
    
    public function getName()
    {
        return 'board';
    }
}
