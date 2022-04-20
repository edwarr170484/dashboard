<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Form\Type\ProductFotoType;
use Dashboard\CommonBundle\Entity\Selltype;

use Dashboard\CommonBundle\Form\DataTransformer\ReviewToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

use Dashboard\CommonBundle\Entity\Category;

class ReviewAnswerType extends AbstractType
{   
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder
            ->add('reviewText', TextareaType::class, array('required' => true, 'label' => 'Jūsu atbilde:', 'attr' => array('class' => 'form-control')))
            ->add('status', ChoiceType::class, array('choices' => array("0" => "Neitrāls","1" => "Pozitīvs", "-1" => "Negatīvs"), 'data' => '0','label' => "Lietotāja vērtējums:",'empty_value' => false,'required' => false, 'attr' => array('class' => 'hidden-input')))
            ->add($builder->create('product', 'hidden',array('attr' => array('class' => 'review_product_id')))->addModelTransformer(new ProductToNumberTransformer($this->manager)))
            ->add($builder->create('review', 'hidden', array('mapped' => false, 'attr' => array('class' => 'review_review_id')))->addModelTransformer(new ReviewToNumberTransformer($this->manager)))
            ->add('save', ButtonType::class, array('label' => 'Sūtīt', 'attr' => array('class' => 'message-button-answer')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Review',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'reviewanswer';
    }
}

