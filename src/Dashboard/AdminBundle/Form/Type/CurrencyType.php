<?php
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Doctrine\Common\Persistence\ObjectManager;

class CurrencyType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('required' => true, 'label' => 'Название:', 'attr' => array('class' => 'form-control')))
            ->add('code', TextType::class, array('required' => true, 'label' => 'Код:', 'attr' => array('class' => 'form-control')))
            ->add('kurs', TextType::class, array('required' => true, 'label' => 'Коэфициент пересчета:', 'attr' => array('class' => 'form-control')))
            ->add('sortorder', TextType::class, array('required' => true, 'label' => 'Порядок:', 'attr' => array('class' => 'form-control')))
            ->add('exit', HiddenType::class, array('required' => false, 'data' => '0', 'mapped' => false));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Currency',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'currency';
    }
}