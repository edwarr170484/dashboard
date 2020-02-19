<?php
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Dashboard\AdminBundle\Form\Type\UserRateItemType;

class UserRateType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rate', 'entity', array('class' => 'DashboardCommonBundle:Rate',
                                          'required' => true, 
                                          'label' => 'Тариф', 
                                          'choice_label' => 'name',
                                          'attr' => array('class' => 'form-control')))
            ->add('category', 'entity', array('class' => 'DashboardCommonBundle:Category',
                                          'required' => false, 
                                          'label' => 'Категория', 
                                          'choice_label' => 'title',
                                          'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->where('c.parent is NULL');},
                                          'attr' => array('class' => 'form-control')))
            ->add('dateStart', DateType::class, array('required' => true, 'label' => 'Дата начала', 'attr' => array('class' => 'form-control')))
            ->add('dateEnd', DateType::class, array('required' => true, 'label' => 'Дата окончания', 'attr' => array('class' => 'form-control')))
            ->add('advertNumber', TextType::class, array('required' => false, 'label' => 'Количество размещений', 'attr' => array('class' => 'form-control')))
            ->add('isActive', CheckboxType::class, array('required' => false, 'label' => 'Активен'))
            ->add('items', CollectionType::class, array('entry_type' => new UserRateItemType(), 'required' => false,'allow_add' => true,'allow_delete' => true, 'by_reference' => false));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\UserRate'
        ));
    }
    
    public function getName()
    {
        return 'rate';
    }
}