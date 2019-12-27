<?php
namespace Dashboard\AdminBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Doctrine\Common\Persistence\ObjectManager;
use Dashboard\CommonBundle\Form\DataTransformer\InfoToNumberTransformer;


class UserInfoType extends AbstractType
{
    public function __construct($em, $user) {
        $this->em = $em;
        $this->user = $user;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array('required' => false, 'label' => 'имя', 'attr' => array('class' => 'form-control','placeholder' => 'Имя')))
            ->add('lastname', TextType::class, array('required' => false, 'label' => 'фамилия', 'attr' => array('class' => 'form-control','placeholder' => 'Фамилия')))
            ->add('phone', TextType::class, array('required' => false, 'label' => 'моб. телефон', 'attr' => array('class' => 'form-control','placeholder' => 'Телефон')))
            ->add('avatarNew', FileType::class, array('required' => false, 'label' => '','mapped' => false, 'attr' => array('class' => 'change-avatar-input form-control')))
            ->add('avatar', HiddenType::class, array('required' => false, 'label' => ''))
            ->add('region', 'entity', array('class' => 'DashboardCommonBundle:Region', 
                                                'required' => false, 
                                                'label' => 'Страна',
                                                'choice_label' => 'name',
                                                'attr' => array('class' => 'form-control hidden-input','id' => 'region','placeholder' => 'Страна')))  
            ->add('city', 'entity', array('class' => 'DashboardCommonBundle:City',
                  'required' => false, 'label' => 'Город', 'choice_label' => 'name',
                  'attr' => array('class' => 'form-control hidden-input','id' => 'city','placeholder' => 'Город')));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dashboard\CommonBundle\Entity\UserInfo'
        ));
    }
    
    public function getName()
    {
        return 'userinfo';
    }
    
    private function array_create($start_num,$stop_num)
    {
        $nums = array();
        
        for($i = 0;$i <= ($stop_num - $start_num);$i++)
        {
            $nums[$start_num + $i] = $start_num + $i;
        }
        
        return $nums;
    }
}

