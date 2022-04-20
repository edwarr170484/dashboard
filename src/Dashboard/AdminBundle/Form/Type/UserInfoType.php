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
            ->add('midname', TextType::class, array('required' => false, 'label' => 'отчество', 'attr' => array('class' => 'form-control','placeholder' => 'Отчество')))
            ->add('phone', TextType::class, array('required' => false, 'label' => 'моб. телефон', 'attr' => array('class' => 'form-control','placeholder' => 'Телефон')))
            ->add('avatarNew', FileType::class, array('required' => false, 'label' => '','mapped' => false, 'attr' => array('class' => 'change-avatar-input form-control')))
            ->add('avatar', HiddenType::class, array('required' => false, 'label' => ''))
            ->add('sex', ChoiceType::class,array('choices' => array('male' => 'Мужской', 'female' => 'Женский'),
                                                 'label' => 'пол',
                                                 'required' => false,
                                                 'attr' => array('class' => 'form-control hidden-input','id' => 'sex','placeholder' => 'пол')))
            ->add('birthdayday', ChoiceType::class, array('choices'  => array(
                '1' => '1','2' => '2','3' => '3',
                '4' => '4','5' => '5','6' => '6',
                '7' => '7','8' => '8','9' => '9',
                '10' => '10','11' => '11', '12' => '12',
                '13' => '13','14' => '14', '15' => '15',
                '16' => '16','17' => '17', '18' => '18',
                '19' => '19','20' => '20', '21' => '21',
                '22' => '22', '23' => '23', '24' => '24',
                '25' => '25', '26' => '26', '27' => '27',
                '28' => '28', '29' => '29', '30' => '30',
                '31' => '31'),'required' => false, 'label' => '', 'attr' => array('class' => 'form-control hidden-input','id' => 'birthdayday','placeholder' => 'день')))
            ->add('birthdaymonth', ChoiceType::class, array('choices'  => array(
                '1' => 'Январь','2' => 'Февраль','3' => 'Март',
                '4' => 'Апрель','5' => 'Май','6' => 'Июнь',
                '7' => 'Июль','8' => 'Август','9' => 'Сентябрь',
                '10' => 'Октябрь','11' => 'Ноябрь', '12' => 'Декабрь')
                ,'required' => false, 'label' => 'месяц', 'attr' => array('class' => 'form-control hidden-input','id' => 'birthdaymonth','placeholder' => 'Месяц')))
            ->add('birthdayyear', ChoiceType::class, array('choices'  => $this->array_create(1920, 2015),'required' => false, 'label' => 'год', 'attr' => array('class' => 'form-control hidden-input','id' => 'birthdayyear','placeholder' => 'Год')))
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

