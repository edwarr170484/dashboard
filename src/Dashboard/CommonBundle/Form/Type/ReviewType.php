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

use Dashboard\CommonBundle\Form\DataTransformer\CategoryToNumberTransformer;
use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

use Dashboard\CommonBundle\Entity\Category;

class ReviewType extends AbstractType
{   
    private $manager;
    private $locale;

    public function __construct(ObjectManager $manager, $locale)
    {
        $this->manager = $manager;
        $this->locale = $locale;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder
            ->add('reviewText', TextareaType::class, array('required' => true, 'label' => 'Jūsu atsauksmes:', 'attr' => array('class' => 'form-control')))
            ->add('mark', 'entity', array('class' => 'DashboardCommonBundle:Mark',
                'choice_label' => function($mark)
                                            {
                                                  if(count($mark->getTranslations()) > 0)
                                                  {
                                                      foreach($mark->getTranslations() as $translation)
                                                      {
                                                          if($translation->getLocale()->getId() == $this->locale->getId())
                                                          {
                                                              return $translation->getValue();
                                                          }
                                                      }
                                                  }
                                                  else
                                                  {
                                                      return $mark->getTitle();
                                                  }
                                            }
                ,'empty_data' => '','required' => false, 'label' => 'Sludinājuma novērtējums:', 'mapped' => false, 'attr' => array('class' => 'hidden-input')))
            ->add('status', ChoiceType::class, array('choices' => array("0" => "<div class='review-ocenka select review-ocenka0'></div><div>Neitrāls</div>",
                                                                        "1" => "<div class='review-ocenka select review-ocenka1'></div><div>Pozitīvs</div>", 
                                                                        "-1" => "<div class='review-ocenka select review-ocenka-1'></div><div>Negatīvs</div>"), 'data' => '0','label' => "Pārdevēja reitings:",'empty_value' => false,'required' => false, 'attr' => array('class' => 'hidden-input')))
            ->add($builder->create('product', 'hidden', array('attr' => array('class' => 'review_product_id')))->addModelTransformer(new ProductToNumberTransformer($this->manager)))
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
        return 'review';
    }
}

