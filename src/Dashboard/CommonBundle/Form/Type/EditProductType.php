<?php
    
namespace Dashboard\CommonBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
use Dashboard\CommonBundle\Entity\Region;

use Dashboard\CommonBundle\Form\DataTransformer\CategoryToNumberTransformer;

use Dashboard\CommonBundle\Entity\Category;

class EditProductType extends AbstractType
{
    private $em;
    private $user;
    private $premiumPrice;
    private $selectedPrice;
    private $translator;
    private $locale;
    
    public function __construct($em, $user, $allservices, $translator, $locale) {
       $this->em = $em;
       $this->user = $user;
       $this->translator = $translator;
       $this->locale = $locale;
       
       foreach($allservices as $service)
       {
           if($service->getId() == 1)
               $this->premiumPrice = $service->getPrice();
           
           if($service->getId() == 2)
               $this->selectedPrice = $service->getPrice();
       }
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {    
        $builder
            ->add('authorName', TextType::class, array('required' => false, 'data' => $this->user->getFirstname(),'label' => 'Jūsu vārds', 'attr' => array('class' => 'form-control')))
            ->add('authorEmail', EmailType::class, array('required' => false, 'data' => $this->user->getUser()->getEmail() ,'label' => 'Jūsu e-pasts', 'attr' => array('class' => 'form-control')))
            ->add('authorPhone', TextType::class, array('required' => false, 'label' => 'Jūsu tālrunis', 'attr' => array('class' => 'form-control')))
            ->add('region', 'entity', array('class' => 'DashboardCommonBundle:Region',
                            'choice_label' => function($region)
                                            {
                                                  if(count($region->getTranslations()) > 0)
                                                  {
                                                      foreach($region->getTranslations() as $translation)
                                                      {
                                                          if($translation->getLocale()->getId() == $this->locale->getId())
                                                          {
                                                              return $translation->getValue();
                                                          }
                                                      }
                                                  }
                                                  else
                                                  {
                                                      return $region->getName();
                                                  }
                                            },
                            'empty_data' => null,
                            'placeholder' => 'Atrašanās vieta',
                            'required' => false,                        
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('r')->orderBy('r.sortorder', 'ASC');},
                            'label' => 'регион', 'attr' => array('class' => 'custom-select')))
            /*->add('typeno', CheckboxType::class, array('required' => false, 'label' => 'Nē', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typeno data-radio=type')))
            ->add('typenew', CheckboxType::class, array('required' => false, 'label' => 'Jauns', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typenew data-radio=type')))
            ->add('typebu', CheckboxType::class, array('required' => false, 'label' => 'Б/У', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typebu data-radio=type')))*/
            ->add($builder->create('category','hidden')->addModelTransformer(new CategoryToNumberTransformer($this->em)))    
            ->add('name', TextType::class, array('required' => false, 'label' => 'Reklāmas nosaukums', 'attr' => array('class' => 'form-control')))
            ->add('term', ChoiceType::class, array('choices' => array("7" => "Nedēļa", "14" => "2 nedēļas", "30" => "Mēnesis", "90" => "3 mēneši"),'label' => 'Termiņš','required' => true, 'attr' => array('class' => 'custom-select')))
            ->add('description', TextareaType::class, array('required' => false, 'label' => 'Apraksts', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('price', TextType::class, array('required' => false, 'label' => 'Cena', 'attr' => array('class' => 'form-control')))
            ->add('fotos', CollectionType::class, array('entry_type' => new ProductFotoType($this->em), 'required' => false,'allow_add' => true,'allow_delete' => true, 'by_reference' => false))    
            ->add('viewcommon', CheckboxType::class, array('required' => false, 'label' => '<span>Regulāra tirdzniecība</span>', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewcommon data-radio=view', 'text' => $this->translator->trans('<span>Regulāra tirdzniecība</span>'))))
            ->add('viewpremium', CheckboxType::class, array('required' => false, 'label' => 'премиум размещение', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewpremium data-radio=view', 'text' => $this->translator->trans('<span>Augstākā izmitināšana - %price% &euro;</span>', array('%price%' => $this->premiumPrice)) )))
            ->add('viewselected', CheckboxType::class, array('required' => false, 'label' => 'выделить объявление', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewselected data-radio=view', 'text' => $this->translator->trans('<span>Iezīmējiet reklāmu - %price% &euro;</span>', array('%price%' => $this->selectedPrice)) )))
            ->add('save', ButtonType::class, array('label' => 'Saglabājiet', 'attr' => array('class' => 'form-main-button')));
                                                
            $formModifier = function (FormInterface $form, Region $region = null) {
            $cities = null === $region ? array() : $region->getCity();

            $form->add('city', 'entity', array('class' => 'DashboardCommonBundle:City',
                                          'choice_label' => function($city)
                                          {
                                                if(count($city->getTranslations()) > 0)
                                                {
                                                    foreach($city->getTranslations() as $translation)
                                                    {
                                                        if($translation->getLocale()->getId() == $this->locale->getId())
                                                        {
                                                            return $translation->getValue();
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    return $city->getName();
                                                }
                                          },
                                          'choices' => $cities,
                                          'required' => false,
                                          'placeholder' => 'Pilsēta/volost',
                                          'query_builder' => function(EntityRepository $er){
                                                return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                                          },
                                          'label' => 'Pilsēta/volost', 'attr' => array('class' => 'custom-select','id' => 'city','placeholder' => 'Pilsēta/volost')));
            };
                           
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getRegion());
                }
            );
            
            $builder->get('region')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $region = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $region);
                }
            );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\Product',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'product';
    }
}



