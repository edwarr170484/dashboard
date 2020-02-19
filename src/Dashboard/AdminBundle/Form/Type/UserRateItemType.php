<?php
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserRateItemType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', 'entity', array('class' => 'DashboardCommonBundle:RateService',
                                          'required' => true, 
                                          'label' => 'Услуга', 
                                          'choice_label' => function($rate){
                                                return $rate->getService()->getTitle();
                                          },
                                          'attr' => array('class' => 'form-control')))
            ->add('count', TextType::class, array('required' => false, 'label' => 'Количество', 'attr' => array('class' => 'form-control')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\UserRateItem'
        ));
    }
    
    public function getName()
    {
        return 'rateitem';
    }
}
