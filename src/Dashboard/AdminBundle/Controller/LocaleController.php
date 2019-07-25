<?php

namespace Dashboard\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

use Dashboard\CommonBundle\Entity\Locale;
use Dashboard\CommonBundle\Entity\Settings;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LocaleController extends Controller
{
    /**
     * @Route("/admin/locale", name="localeAdmin")
     */
    public function localeTableAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locales = $manager->getRepository("DashboardCommonBundle:Locale")->findBy(array(), array("sortorder" => "ASC"));
        $notice = '';
        
        $form = $this->createFormBuilder()->add('action','hidden',array('attr' => array('value' => 'save')))->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            switch($form['action']->getData())
            {
                case 'save':
                    
                    if($request->request->get('localeActive'))
                    {
                        foreach($locales as $locale)
                        {
                            if(!$locale->getIsDefault())
                            {
                                $locale->setIsActive(0);
                            }
                            $manager->persist($locale);
                        }
                    }

                    if($request->request->get('localeDefault'))
                    {
                        foreach($locales as $locale)
                        {
                            $locale->setIsDefault(0);
                            $manager->persist($locale);
                        }
                    }
                    
                    $manager->flush();
                    
                    if($request->request->get('localeActive'))
                    {
                        foreach($request->request->get('localeActive') as $key => $value)
                        {
                            $locale = $manager->getRepository("DashboardCommonBundle:Locale")->find($key);
                            if($locale)
                            {
                                $locale->setIsActive(1);
                                $manager->persist($locale);
                            }
                        }
                    }
                    
                    if($request->request->get('localeDefault'))
                    {
                        foreach($request->request->get('localeDefault') as $key => $value)
                        {
                            $locale = $manager->getRepository("DashboardCommonBundle:Locale")->find($value);
                            if($locale)
                            {
                                $locale->setIsDefault(1);
                                $manager->persist($locale);
                                break;
                            }
                        }
                    }
                    
                    if($request->request->get('sortorder'))
                    {
                        foreach($request->request->get('sortorder') as $key => $value)
                        {
                            $locale = $manager->getRepository("DashboardCommonBundle:Locale")->find($key);
                            if($locale)
                            {
                                $locale->setSortorder($value);
                                $manager->persist($locale);
                            }
                        }
                    }
                    
                    $notice =  $this->get('translator')->trans('<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Выполнено!</strong> Изменения сохранены.</div>');
                    
                    $manager->flush();
                    
                break;
                
                case 'delete':
                    
                    if($request->request->get('localeId'))
                    {
                        foreach($request->request->get('localeId') as $localeId)
                        {
                            $locale = $manager->getRepository("DashboardCommonBundle:Locale")->find($localeId);
                            if($locale)
                            {
                                if($locale->getIsDefault())
                                {
                                    $notice =  $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Ошибка!</strong> ' . $locale->getName() . ' язык установлен по умолчанию и не может быть удален.</div>');
                                }
                                else
                                {
                                    if($locale->getCode())
                                    {
                                        if (file_exists('../app/Resources/translations/messages.' . $locale->getCode() . '.xlf'))
                                            unlink('../app/Resources/translations/messages.' . $locale->getCode() . '.xlf');
                                    }
                                    
                                    $manager->remove($locale);
                                    
                                    $notice =  $this->get('translator')->trans('<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Выполнено!</strong> Изменения сохранены.</div>');
                                }
                            }
                        }
                        
                        $manager->flush();
                    }
                    
                break;
            }
            
            $this->addFlash('notice', $notice);
            
            return $this->redirectToRoute("localeAdmin");
        }
        
        return $this->render('DashboardAdminBundle:Locale:locale.html.twig', array("locales" => $locales, "form" => $form->createView()));
    }
    
    /**
     * @Route("/admin/localeedit/{localeId}", name="editLocaleAdmin", defaults={"localeId" : 0})
     */
    public function editLocaleAction($localeId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $defaultLocale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("isDefault" => true));
        
        if($localeId)
        {
            $locale = $manager->getRepository("DashboardCommonBundle:Locale")->find($localeId);
            
            if(!$locale)
                return $this->redirectToRoute ("editLocaleAdmin");
        }
        else
            $locale = new Locale();
        
        if($locale->getCode())
        {
            if (file_exists('../app/Resources/translations/messages.' . $locale->getCode() . '.xlf'))
                $translationFile = file_get_contents('../app/Resources/translations/messages.' . $locale->getCode() . '.xlf');
            
        }
        else
        {
            if (file_exists('../app/Resources/translations/messages.' . $defaultLocale->getCode() . '.xlf'))
                $translationFile = file_get_contents('../app/Resources/translations/messages.' . $defaultLocale->getCode() . '.xlf');
        }
        
        $localeForm = $this->get('form.factory')->createNamedBuilder('locale', 'form', $locale)
            ->add('name', TextType::class, array('required' => true, 'label' => 'Название:', 'attr' => array('class' => 'form-control')))
            ->add('code', TextType::class, array('required' => true, 'label' => 'Код языка:', 'attr' => array('class' => 'form-control')))    
            ->add('sortorder', TextType::class, array('required' => false, 'label' => 'Порядок:', 'attr' => array('class' => 'form-control')))
            ->add('country', ChoiceType::class, array('choices' => array('Afghanistan.png' => 'Afghanistan','African Union.png' => 'African Union','Albania.png' => 'Albania','Algeria.png' => 'Algeria','American Samoa.png' => 'American Samoa','Andorra.png' => 'Andorra','Angola.png' => 'Angola','Anguilla.png' => 'Anguilla','Antarctica.png' => 'Antarctica','Antigua & Barbuda.png' => 'Antigua & Barbuda','Arab League.png' => 'Arab League','Argentina.png' => 'Argentina','Armenia.png' => 'Armenia','Aruba.png' => 'Aruba','ASEAN.png' => 'ASEAN','Australia.png' => 'Australia','Austria.png' => 'Austria','Azerbaijan.png' => 'Azerbaijan','Bahamas.png' => 'Bahamas','Bahrain.png' => 'Bahrain','Bangladesh.png' => 'Bangladesh','Barbados.png' => 'Barbados','Belarus.png' => 'Belarus','Belgium.png' => 'Belgium','Belize.png' => 'Belize','Benin.png' => 'Benin','Bermuda.png' => 'Bermuda','Bhutan.png' => 'Bhutan','Bolivia.png' => 'Bolivia','Bosnia & Herzegovina.png' => 'Bosnia & Herzegovina','Botswana.png' => 'Botswana','Brazil.png' => 'Brazil','Brunei.png' => 'Brunei','Bulgaria.png' => 'Bulgaria','Burkina Faso.png' => 'Burkina Faso','Burundi.png' => 'Burundi','Cambodja.png' => 'Cambodja','Cameroon.png' => 'Cameroon','Canada.png' => 'Canada','Cape Verde.png' => 'Cape Verde','CARICOM.png' => 'CARICOM','Cayman Islands.png' => 'Cayman Islands','Central African Republic.png' => 'Central African Republic','Chad.png' => 'Chad','Chile.png' => 'Chile','China.png' => 'China','CIS.png' => 'CIS','Colombia.png' => 'Colombia','Commonwealth.png' => 'Commonwealth','Comoros.png' => 'Comoros','Congo-Brazzaville.png' => 'Congo-Brazzaville','Congo-Kinshasa(Zaire).png' => 'Congo-Kinshasa(Zaire)','Cook Islands.png' => 'Cook Islands','Costa Rica.png' => 'Costa Rica','Cote d\'Ivoire.png' => 'Cote d\'Ivoire','Croatia.png' => 'Croatia','Cuba.png' => 'Cuba','Cyprus.png' => 'Cyprus','Czech Republic.png' => 'Czech Republic','Denmark.png' => 'Denmark','Djibouti.png' => 'Djibouti','Dominica.png' => 'Dominica','Dominican Republic.png' => 'Dominican Republic','Ecuador.png' => 'Ecuador','Egypt.png' => 'Egypt','El Salvador.png' => 'El Salvador','England.png' => 'England','Equatorial Guinea.png' => 'Equatorial Guinea','Eritrea.png' => 'Eritrea','Estonia.png' => 'Estonia','Ethiopia.png' => 'Ethiopia','European Union.png' => 'European Union','Faroes.png' => 'Faroes','Fiji.png' => 'Fiji','Finland.png' => 'Finland','France.png' => 'France','Gabon.png' => 'Gabon','Gambia.png' => 'Gambia','Georgia.png' => 'Georgia','Germany.png' => 'Germany','Ghana.png' => 'Ghana','Gibraltar.png' => 'Gibraltar','Greece.png' => 'Greece','Greenland.png' => 'Greenland','Grenada.png' => 'Grenada','Guadeloupe.png' => 'Guadeloupe','Guam.png' => 'Guam','Guatemala.png' => 'Guatemala','Guernsey.png' => 'Guernsey','Guinea.png' => 'Guinea','Guinea-Bissau.png' => 'Guinea-Bissau','Guyana.png' => 'Guyana','Haiti.png' => 'Haiti','Honduras.png' => 'Honduras','Hong Kong.png' => 'Hong Kong','Hungary.png' => 'Hungary','Iceland.png' => 'Iceland','India.png' => 'India','Indonezia.png' => 'Indonezia','Iran.png' => 'Iran','Iraq.png' => 'Iraq','Ireland.png' => 'Ireland','Islamic Conference.png' => 'Islamic Conference','Isle of Man.png' => 'Isle of Man','Israel.png' => 'Israel','Italy.png' => 'Italy','Jamaica.png' => 'Jamaica','Japan.png' => 'Japan','Jersey.png' => 'Jersey','Jordan.png' => 'Jordan','Kazakhstan.png' => 'Kazakhstan','Kenya.png' => 'Kenya','Kiribati.png' => 'Kiribati','Kosovo.png' => 'Kosovo','Kuwait.png' => 'Kuwait','Kyrgyzstan.png' => 'Kyrgyzstan','Laos.png' => 'Laos','Latvia.png' => 'Latvia','Lebanon.png' => 'Lebanon','Lesotho.png' => 'Lesotho','Liberia.png' => 'Liberia','Libya.png' => 'Libya','Liechtenshein.png' => 'Liechtenshein','Lithuania.png' => 'Lithuania','Luxembourg.png' => 'Luxembourg','Macao.png' => 'Macao','Macedonia.png' => 'Macedonia','Madagascar.png' => 'Madagascar','Malawi.png' => 'Malawi','Malaysia.png' => 'Malaysia','Maldives.png' => 'Maldives','Mali.png' => 'Mali','Malta.png' => 'Malta','Marshall Islands.png' => 'Marshall Islands','Martinique.png' => 'Martinique','Mauritania.png' => 'Mauritania','Mauritius.png' => 'Mauritius','Mexico.png' => 'Mexico','Micronesia.png' => 'Micronesia','Moldova.png' => 'Moldova','Monaco.png' => 'Monaco','Mongolia.png' => 'Mongolia','Montenegro.png' => 'Montenegro','Montserrat.png' => 'Montserrat','Morocco.png' => 'Morocco','Mozambique.png' => 'Mozambique','Myanmar(Burma).png' => 'Myanmar(Burma)','Namibia.png' => 'Namibia','NATO.png' => 'NATO','Nauru.png' => 'Nauru','Nepal.png' => 'Nepal','Netherlands.png' => 'Netherlands','Netherlands Antilles.png' => 'Netherlands Antilles','New Caledonia.png' => 'New Caledonia','New Zealand.png' => 'New Zealand','Nicaragua.png' => 'Nicaragua','Niger.png' => 'Niger','Nigeria.png' => 'Nigeria','Northern Cyprus.png' => 'Northern Cyprus','Northern Ireland.png' => 'Northern Ireland','North Korea.png' => 'North Korea','Norway.png' => 'Norway','Olimpic Movement.png' => 'Olimpic Movement','Oman.png' => 'Oman','OPEC.png' => 'OPEC','Pakistan.png' => 'Pakistan','Palau.png' => 'Palau','Palestine.png' => 'Palestine','Panama.png' => 'Panama','Papua New Guinea.png' => 'Papua New Guinea','Paraguay.png' => 'Paraguay','Peru.png' => 'Peru','Philippines.png' => 'Philippines','Poland.png' => 'Poland','Portugal.png' => 'Portugal','Puerto Rico.png' => 'Puerto Rico','Qatar.png' => 'Qatar','Red Cross.png' => 'Red Cross','Reunion.png' => 'Reunion','Romania.png' => 'Romania','Russia.png' => 'Russia','Rwanda.png' => 'Rwanda','Saint Lucia.png' => 'Saint Lucia','Samoa.png' => 'Samoa','San Marino.png' => 'San Marino','Sao Tome & Principe.png' => 'Sao Tome & Principe','Saudi Arabia.png' => 'Saudi Arabia','Scotland.png' => 'Scotland','Senegal.png' => 'Senegal','Serbia(Yugoslavia).png' => 'Serbia(Yugoslavia)','Seychelles.png' => 'Seychelles','Sierra Leone.png' => 'Sierra Leone','Singapore.png' => 'Singapore','Slovakia.png' => 'Slovakia','Slovenia.png' => 'Slovenia','Solomon Islands.png' => 'Solomon Islands','Somalia.png' => 'Somalia','Somaliland.png' => 'Somaliland','South Africa.png' => 'South Africa','South Korea.png' => 'South Korea','Spain.png' => 'Spain','Sri Lanka.png' => 'Sri Lanka','St Kitts & Nevis.png' => 'St Kitts & Nevis','St Vincent & the Grenadines.png' => 'St Vincent & the Grenadines','Sudan.png' => 'Sudan','Suriname.png' => 'Suriname','Swaziland.png' => 'Swaziland','Sweden.png' => 'Sweden','Switzerland.png' => 'Switzerland','Syria.png' => 'Syria','Tahiti(French Polinesia).png' => 'Tahiti(French Polinesia)','Taiwan.png' => 'Taiwan','Tajikistan.png' => 'Tajikistan','Tanzania.png' => 'Tanzania','Thailand.png' => 'Thailand','Timor-Leste.png' => 'Timor-Leste','Togo.png' => 'Togo','Tonga.png' => 'Tonga','Trinidad & Tobago.png' => 'Trinidad & Tobago','Tunisia.png' => 'Tunisia','Turkey.png' => 'Turkey','Turkmenistan.png' => 'Turkmenistan','Turks and Caicos Islands.png' => 'Turks and Caicos Islands','Tuvalu.png' => 'Tuvalu','Uganda.png' => 'Uganda','Ukraine.png' => 'Ukraine','United-Kingdom.png' => 'United-Kingdom','United Arab Emirates.png' => 'United Arab Emirates','United Nations.png' => 'United Nations','Uruguay.png' => 'Uruguay','USA.png' => 'USA','Uzbekistan.png' => 'Uzbekistan','Vanutau.png' => 'Vanutau','Vatican City.png' => 'Vatican City','Venezuela.png' => 'Venezuela','Viet Nam.png' => 'Viet Nam','Virgin Islands British.png' => 'Virgin Islands British','Virgin Islands US.png' => 'Virgin Islands US','Wales.png' => 'Wales','Western Sahara.png' => 'Western Sahara','Yemen.png' => 'Yemen','Zambia.png' => 'Zambia','Zimbabwe.png' => 'Zimbabwe'),'required' => true, 'label' => 'Страна:', 'attr' => array('class' => 'form-control'))) 
            ->add('localeFile', TextareaType::class, array('required' => false, 'data' => $translationFile,'label' => 'Файл перевода:', 'mapped' => false, 'attr' => array('class' => 'form-control', 'rows' => '40')))
             ->add('currency', 'entity', array('class' => 'DashboardCommonBundle:Currency',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->orderBy('c.sortorder', 'ASC');},
                            'label' => 'Валюта:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => 'Валюта:')))   
            ->add('exit', HiddenType::class, array('required' => false, 'data' => '0', 'mapped' => false))
            ->getForm();
        
        $localeForm->handleRequest($request);
        
        if($localeForm->isSubmitted() && $localeForm->isValid())
        {
            //create translation file template
            $translationFileName = 'messages.' . $localeForm['code']->getData() . '.xlf';
            file_put_contents('../app/Resources/translations/' . $translationFileName, $localeForm['localeFile']->getData());
                        
            $manager->persist($locale);
            $manager->flush();
            
            //create settings for location if it's does not exists
            $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
            
            if(!$settings)
            {
                $newSettings = new Settings();
                $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $defaultLocale));
                $newSettings = clone $settings;
                
                $newSettings->setLocale($locale);
                $manager->persist($newSettings);
            }
            
            //create service pages if not exixts for this locale
            $page = $manager->getRepository("DashboardCommonBundle:Page")->findBy(array("locale" => $locale));
            
            if(!$page || count($page) == 0)
            {
                $defaultPages = $manager->getRepository("DashboardCommonBundle:Page")->findBy(array("locale" => $defaultLocale));
                
                if($defaultPages)
                {
                    foreach($defaultPages as $defaultPage)
                    {
                        $newPage = new Page();
                        $newPage = clone $defaultPage;
                        $newPage->setLocale($locale);
                        $manager->persist($newPage);
                    }
                }
            }
            
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Выполнено!</strong> Изменения сохранены.</div>')
            );
            
            if($localeForm['exit']->getData())
            {
                return $this->redirectToRoute("localeAdmin");
            }
            
            return $this->redirectToRoute("editLocaleAdmin", array("localeId" => $locale->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Locale:editlocale.html.twig', array("localeId" => $localeId,"localeForm" => $localeForm->createView()));
    }
}

