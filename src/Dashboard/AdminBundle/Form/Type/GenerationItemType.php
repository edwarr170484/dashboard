<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\AdminBundle\Form\DataTransformer\GenerationToNumberTransformer;

class GenerationItemType extends AbstractType
{
    private $manager;
    private $generations;
    
    public function __construct($manager, $generations) {
       $this->manager = $manager;
       $this->generations = $generations;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $boards = new ArrayCollection();
        $modifications = new ArrayCollection();
        
        foreach($this->generations as $generation){
            if($generation->getBoards()){
                foreach($generation->getBoards() as $board){
                    $boards->add($board);
                }
            }
            if($generation->getModifications()){
                foreach($generation->getModifications() as $modification){
                    $modifications->add($modification);
                }
            }
        }
        
        $builder
            ->add('board', 'entity', array('class' => 'DashboardCommonBundle:GenerationBoard',
                                        'choices' => $boards,
                                        'choice_label' => 'board.value',
                                        'required' => true,
                                        'group_by' => 'generation.name',
                                        'required' => false,
                                        'attr' => array('class' => 'form-control')))
            ->add('gasType', 'entity', array('class' => 'DashboardCommonBundle:FilterValue',
                           'choice_label' => 'value',
                           'required' => true, 
                           'query_builder' => function(EntityRepository $er){
                                $filter = $this->manager->getRepository("DashboardCommonBundle:Filter")->find(16);
                                return $er->createQueryBuilder('v')->where('v.filter = :filter')->setParameter('filter',$filter);   
                           },
                           'label' => 'Двигатель:', 'attr' => array('class' => 'form-control')))
            ->add('transmissionType', 'entity', array('class' => 'DashboardCommonBundle:FilterValue',
                           'choice_label' => 'value',
                           'required' => true, 
                           'query_builder' => function(EntityRepository $er){
                                $filter = $this->manager->getRepository("DashboardCommonBundle:Filter")->find(17);
                                return $er->createQueryBuilder('v')->where('v.filter = :filter')->setParameter('filter',$filter);   
                           },
                           'label' => 'Привод:', 'attr' => array('class' => 'form-control')))
            ->add('gearType', 'entity', array('class' => 'DashboardCommonBundle:FilterValue',
                           'choice_label' => 'value',
                           'required' => true, 
                           'query_builder' => function(EntityRepository $er){
                                $filter = $this->manager->getRepository("DashboardCommonBundle:Filter")->find(18);
                                return $er->createQueryBuilder('v')->where('v.filter = :filter')->setParameter('filter',$filter);   
                           },
                           'label' => 'Коробка:', 'attr' => array('class' => 'form-control')))
            ->add('itemModifications', 'entity', array('class' => 'DashboardCommonBundle:Modification',
                                        'choices' => $modifications,
                                        'choice_label' => function($modification){
                                                return $modification->getPower() . "," . $modification->getSize() . "," . $modification->getLabel();},
                                        'group_by' => 'generation.name',                
                                        'multiple' => true,
                                        'required' => false,
                                        'attr' => array('class' => 'form-control')))
            ->add($builder->create('generation', 'hidden')->addModelTransformer(new GenerationToNumberTransformer($this->manager)));      
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\GenerationItem'
        ));
    }
    
    public function getName()
    {
        return 'item';
    }
}
