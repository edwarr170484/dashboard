<?php
    
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Dashboard\CommonBundle\Entity\Filter;

use Dashboard\AdminBundle\Form\DataTransformer\FilterToNumberTransformer;
use Dashboard\AdminBundle\Form\Type\TranslationType;

class FilterValueType extends AbstractType
{   
    private $manager;
    
    public function __construct($manager) {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('value', TextType::class, array('required' => true,'label' => 'Значение для фильтра', 'attr' => array('class' => 'form-control', 'placeholder' => 'Значение для фильтра')))
            ->add('translations', 'collection', array('type' => new TranslationType($this->manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('linkedFilters', 'entity',array('class' => 'DashboardCommonBundle:Filter',
                                                  'choice_label' => 'name',
                                                  'required' => false,
                                                  'multiple' => true,
                                                  'query_builder' => function(EntityRepository $er)
                                                  {return $er->createQueryBuilder('f')->orderBy('f.sortorder', 'ASC');},
                                                  'attr' => array('class' => 'form-control')))
            ->add($builder->create('filter', 'hidden')->addModelTransformer(new FilterToNumberTransformer($this->manager)));    
        
        $formModifier = function (FormInterface $form, Filter $filter = null) {
            $values = null === $filter ? array() : $filter->getValues();
            $form->add('linkedValues', 'entity', array('class' => 'DashboardCommonBundle:FilterValue',
                                        'choice_label' => 'value',
                                        'choices' => $values,
                                        'required' => false,
                                        'multiple' => true,
                                        'attr' => array('class' => 'form-control')));
        };
        
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                if($data){
                    $formModifier($event->getForm(), $data->getFilter());
                }else{
                    $formModifier($event->getForm(), null);
                }
                
            }
        );
            
        $builder->get('linkedFilters')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $linkedFilter = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $linkedFilter[0]);
            }
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\FilterValue'
        ));
    }
    
    public function getName()
    {
        return 'filtervalue';
    }
}




