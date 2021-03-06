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

class UserAddAdvertType extends AbstractType
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
            ->add('authorName', TextType::class, array('required' => false, 'data' => $this->user->getFirstname(),'label' => 'J??su v??rds', 'attr' => array('class' => 'form-control')))
            ->add('authorEmail', EmailType::class, array('required' => false, 'data' => $this->user->getUser()->getEmail() ,'label' => 'J??su e-pasts', 'attr' => array('class' => 'form-control')))
            ->add('authorPhone', TextType::class, array('required' => false, 'data' => $this->user->getPhone() ,'label' => 'J??su t??lrunis', 'attr' => array('class' => 'form-control')))
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
                    'label' => 'Atra??an??s vieta', 
                    'placeholder' => 'Atra??an??s vieta',
                    'required' => false,
                    'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('r')->orderBy('r.sortorder', 'ASC');},
                    'attr' => array('class' => 'custom-select','id' => 'region', 'placeholder' => 'Atra??an??s vieta')))
            /*->add('typeno', CheckboxType::class, array('required' => false, 'label' => 'N??', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typeno data-radio=type')))
            ->add('typenew', CheckboxType::class, array('required' => false, 'label' => 'Jauns', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typenew data-radio=type')))
            ->add('typebu', CheckboxType::class, array('required' => false, 'label' => '??/??', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=typebu data-radio=type')))*/
            ->add($builder->create('category','hidden')->addModelTransformer(new CategoryToNumberTransformer($this->em)))    
            ->add('name', TextType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
            ->add('term', ChoiceType::class, array('choices' => array("7" => "Ned????a", "14" => "2 ned????as", "30" => "M??nesis", "90" => "3 m??ne??i"),'required' => true, 'attr' => array('class' => 'custom-select')))
            ->add('description', TextareaType::class, array('required' => false, 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('price', TextType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
            ->add('fotos', CollectionType::class, array('entry_type' => new ProductFotoType($this->em), 'allow_add' => true,'allow_delete' => true, 'by_reference' => false))    
            ->add('viewcommon', CheckboxType::class, array('required' => false, 'label' => '?????????????? ??????????????', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewcommon data-radio=view', 'text' => $this->translator->trans('<span>Regul??ra tirdzniec??ba</span>'))))
            ->add('viewpremium', CheckboxType::class, array('required' => false, 'label' => '?????????????? ????????????????????', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewpremium data-radio=view', 'text' => $this->translator->trans('<span>Augst??k?? izmitin????ana - %price% &euro;</span>', array('%price%' => $this->premiumPrice)) )))
            ->add('viewselected', CheckboxType::class, array('required' => false, 'label' => '???????????????? ????????????????????', 'attr' => array('class' => 'hidden-input', 'id' => 'data-checkbox=viewselected data-radio=view', 'text' => $this->translator->trans('<span>Iez??m??jiet rekl??mu - %price% &euro;</span>', array('%price%' => $this->selectedPrice)))))
            ->add('termsAccept', CheckboxType::class, array(
                'mapped' => false,
                'required' => false,
                'data' => false,
                'attr' =>  array('class' => 'hidden-input', 'id' => 'data-checkbox=termsAccept', 
                'text' => $this->translator->trans('<div class="agreement-text">*Es piekr??tu pakalpojuma <a href="" data-toggle="modal" data-target="#userAgreementModal"> lieto??anas noteikumiem </a>, k?? ar?? manu datu p??rs??t????anai un apstr??dei uz gribupardot.sunweb.by. Es apstiprinu savu vair??kumu un atbild??bu par rekl??mas izvieto??anu.</div>'))))
            ->add('save', ButtonType::class, array('label' => 'Public??t', 'attr' => array('class' => 'form-main-button')));
                                                
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
                                          'placeholder' => 'Pils??ta/volost',
                                          'query_builder' => function(EntityRepository $er){
                                                return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                                          },
                                          'label' => 'Pils??ta/volost', 'attr' => array('class' => 'custom-select','id' => 'city','placeholder' => 'Pils??ta/volost')));
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

