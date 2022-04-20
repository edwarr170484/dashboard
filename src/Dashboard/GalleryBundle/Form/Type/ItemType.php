<?php

namespace Dashboard\GalleryBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Dashboard\GalleryBundle\Form\DataTransformer\GalleryToNumberTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class ItemType extends AbstractType
{    
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('description', 'textarea', array('required' => false, 'label' => 'Текст, размещенный на картинке', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('imageNew', 'file', array('required' => false, 'label' => 'Image','mapped' => false, 'attr' => array('class' => 'form-control m-t-15')))
            ->add('image', 'hidden', array('required' => true, 'label' => ''))
            ->add('alt', 'text', array('required' => false, 'label' => 'Alt', 'attr' => array('class' => 'form-control','placeholder' => 'Alt')))
            ->add('title', 'text', array('required' => false, 'label' => 'Title', 'attr' => array('class' => 'form-control', 'placeholder' => 'Title')))
            ->add('sort', 'text', array('required' => true, 'label' => 'Order', 'attr' => array('class' => 'form-control')))
            ->add('status', 'checkbox', array('required' => false))
            ->add($builder->create('gallery', 'hidden')->addModelTransformer(new GalleryToNumberTransformer($this->manager)));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\GalleryBundle\Entity\GalleryItems',
            'translation_domain' => 'messages'
        ));
    }
    
    public function getName()
    {
        return 'item';
    }
    
}


