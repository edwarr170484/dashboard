<?php
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Dashboard\CommonBundle\Form\DataTransformer\DealerInfoToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\TimeToTextTransformer;

class WorkInfoType extends AbstractType
{
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monday', CheckboxType::class, array('required' => false, 'label' => 'пн'))
            ->add('tuesday', CheckboxType::class, array('required' => false, 'label' => 'вт'))
            ->add('wednesday', CheckboxType::class, array('required' => false, 'label' => 'ср'))
            ->add('thursday', CheckboxType::class, array('required' => false, 'label' => 'чт'))    
            ->add('friday', CheckboxType::class, array('required' => false, 'label' => 'пт'))    
            ->add('saturday', CheckboxType::class, array('required' => false, 'label' => 'сб')) 
            ->add('sunday', CheckboxType::class, array('required' => false, 'label' => 'вс')) 
            ->add('fullDay', CheckboxType::class, array('required' => false, 'label' => '24 ч.'))
            ->add('isWokdays', CheckboxType::class, array('required' => false, 'label' => 'будни'))
            ->add('isHolidays', CheckboxType::class, array('required' => false, 'label' => 'выходные'))
            ->add('isAlldays', CheckboxType::class, array('required' => false, 'label' => 'ежедневно'))
            ->add('workStart', TextType::class, array('required' => false, 'label' => '', 'attr' => array('class' => 'workTime','placeholder' => '00:00')))
            ->add('workStop', TextType::class, array('required' => false, 'label' => '', 'attr' => array('class' => 'workTime','placeholder' => '00:00')))
            ->add('breakStart', TextType::class, array('required' => false, 'label' => '', 'attr' => array('class' => 'workTime','placeholder' => '00:00')))
            ->add('breakStop', TextType::class, array('required' => false, 'label' => '', 'attr' => array('class' => 'workTime','placeholder' => '00:00')))
            ->add($builder->create('dealer', 'hidden')->addModelTransformer(new DealerInfoToNumberTransformer($this->em)));
        
        $builder->get('workStart')->addModelTransformer(new TimeToTextTransformer($this->em));
        $builder->get('workStop')->addModelTransformer(new TimeToTextTransformer($this->em));
        $builder->get('breakStart')->addModelTransformer(new TimeToTextTransformer($this->em));
        $builder->get('breakStop')->addModelTransformer(new TimeToTextTransformer($this->em)); 
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Workinfo',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'workinfo';
    }
}