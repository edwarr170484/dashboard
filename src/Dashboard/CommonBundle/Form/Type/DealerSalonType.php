<?php
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Dashboard\CommonBundle\Form\DataTransformer\DealerInfoToNumberTransformer;
use Dashboard\CommonBundle\Form\Type\WorkInfoType;
use Dashboard\CommonBundle\Form\Type\DealerSalonPhoneType;

class DealerSalonType extends AbstractType
{
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('required' => true, 'label' => 'Название', 'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array('required' => true, 'label' => 'Адрес', 'attr' => array('class' => 'form-control')))
            ->add('website', TextType::class, array('required' => false, 'label' => 'Сайт', 'attr' => array('class' => 'form-control')))
            ->add('logotypeNew', FileType::class, array('required' => false, 'label' => 'Изображение','mapped' => false, 'attr' => array('class' => 'change-avatar-input')))
            ->add('logotype', HiddenType::class, array('required' => false, 'label' => ''))
            ->add('phones', CollectionType::class, array('entry_type' => new DealerSalonPhoneType($this->em), 'required' => false,'allow_add' => true,'allow_delete' => true, 'by_reference' => false,'label' => 'Телефоны')) 
            ->add($builder->create('dealerInfo', 'hidden')->addModelTransformer(new DealerInfoToNumberTransformer($this->em)))
            ->add('workinfo', new WorkInfoType($this->em), array('data_class' => 'Dashboard\CommonBundle\Entity\Workinfo'));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\DealerSalon',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'dealersalon';
    }
}

