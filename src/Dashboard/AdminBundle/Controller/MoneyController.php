<?php
namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Collections\ArrayCollection;

class MoneyController extends Controller
{  
    /**
     * @Route("/admin/invoice", name="admin_invoices")
     */
    public function invoiceAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $user = $this->get('security.context')->getToken()->getUser();
        
        $bills = $manager->getRepository("DashboardCommonBundle:Bill")->findAll();
        $rateBills = $manager->getRepository("DashboardCommonBundle:RateBill")->findAll();
        
        if($request->request->get('action')){
            switch($request->request->get('action')){
                case 'download':
                    $files = new ArrayCollection();
                    $finder = new Finder();
                    $finder->files()->in($request->server->get('DOCUMENT_ROOT') . '/docs/');
            
                    foreach ($finder as $file) {
                        $files->add($file);
                    }
                    
                    if(count($files) > 0){
                        $zip = new \ZipArchive();
                        $zipName = 'Bills.zip';
                        
                        $zip->open($zipName,  \ZipArchive::CREATE);
                        if($request->request->get('className')){
                            foreach($request->request->get('className') as $className){
                                $sql = "SELECT b FROM Dashboard\\CommonBundle\\Entity\\" . $className . " b WHERE 1=1";
                                if($request->request->get('dateStart') && $request->request->get('dateStart') > 0){
                                    $sql .= " AND b.dateAdded >= '" . $request->request->get('dateStart')  . "'";
                                }
                                if($request->request->get('dateEnd') && $request->request->get('dateEnd') > 0){
                                    $sql .= " AND b.dateAdded <= '" . $request->request->get('dateEnd')  . "'";
                                }
                                $query = $manager->createQuery($sql);
                                $bills = $query->getResult();
                                
                                if(count($bills) > 0){
                                    foreach($bills as $bill){
                                        foreach ($files as $file) {
                                            if($file->getFilename() == "invoice-#" . $bill->getId() . ".pdf"){
                                                $zip->addFromString($file->getFilename(),  $file->getContents());
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $zip->close();
                        if(file_exists($zipName)){
                            $response = new Response(file_get_contents($zipName));
                            $response->headers->set('Content-Type', 'application/zip');
                            $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
                            $response->headers->set('Content-length', filesize($zipName));

                            @unlink($zipName);

                            return $response;
                        }else{
                            return $this->redirectToRoute('admin_invoices');
                        }
                        
                    }else{
                        return $this->redirectToRoute('admin_invoices');
                    }
                    
                break;
            
                case 'excel':
                    $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
                    $phpExcelObject->getProperties()->setCreator($user->getUserinfo()->getFirstname() . ' ' . $user->getUserinfo()->getLastname())
                                                ->setTitle("Счета_" . date("d-m-Y"))
                                                ->setDescription("Счета_" . date("d-m-Y"));
                    $phpExcelObject->setActiveSheetIndex(0)
                               ->setCellValue('A1', 'FECHA')
                               ->setCellValue('B1', 'CTA.VENTAS')
                               ->setCellValue('C1', 'TOTAL INGRESO')
                               ->setCellValue('D1', 'BASE')
                               ->setCellValue('E1', 'IVA ' . ($settings->getPremiumAdvPrice() * 100) . '%')
                               ->setCellValue('F1', 'Nº CTA CLIENTE')
                               ->setCellValue('G1', 'RAZON SOCIAL CLIENTE')
                               ->setCellValue('H1', 'N.I.F.')
                               ->setCellValue('I1', 'NUM DE FACT.');
                    
                    if($request->request->get('className')){
                        $i = 2;
                        foreach($request->request->get('className') as $className){
                            $sql = "SELECT b FROM Dashboard\\CommonBundle\\Entity\\" . $className . " b WHERE 1=1";
                            if($request->request->get('dateStart') && $request->request->get('dateStart') > 0){
                                $sql .= " AND b.dateAdded >= '" . $request->request->get('dateStart')  . "'";
                            }
                            if($request->request->get('dateEnd') && $request->request->get('dateEnd') > 0){
                                $sql .= " AND b.dateAdded <= '" . $request->request->get('dateEnd')  . "'";
                            }
                            $query = $manager->createQuery($sql);
                            $bills = $query->getResult();
                            
                            
                            if(count($bills) > 0){
                                foreach($bills as $bill){
                                    $phpExcelObject->setActiveSheetIndex(0)
                                                    ->setCellValue('A' . $i, $bill->getDateAdded()->format('d.m.Y'))
                                                    ->setCellValue('B' . $i, '70500000')
                                                    ->setCellValue('C' . $i, $bill->getPrice() + round($bill->getPrice() * $settings->getPremiumAdvPrice()))
                                                    ->setCellValue('D' . $i, $bill->getPrice())
                                                    ->setCellValue('E' . $i, round($bill->getPrice() * $settings->getPremiumAdvPrice()))
                                                    ->setCellValue('F' . $i, $bill->getUser()->getId());
                                    if($bill->getUser()->getRoles()[0]->getRole() == 'ROLE_DEALER' || $bill->getUser()->getRoles()[0]->getRole() == 'ROLE_SERVICE'){
                                        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('G' . $i, $bill->getUser()->getDealerinfo()->getCompany())
                                                       ->setCellValue('H' . $i, $bill->getUser()->getDealerinfo()->getNifNumber());
                                    }else{
                                        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('G' . $i, $bill->getUser()->getUserinfo()->getFirstname() . ' ' . $bill->getUser()->getUserinfo()->getLastname())
                                                       ->setCellValue('H' . $i, '');
                                    }
                                    $phpExcelObject->setActiveSheetIndex(0)->setCellValue('I' . $i, $bill->getId());
                                    $i++;
                                }
                            }
                        } 
                    }
                    
                    $phpExcelObject->getActiveSheet()->setTitle('Список счетов');
                    $phpExcelObject->setActiveSheetIndex(0);
                    $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
                    $response = $this->get('phpexcel')->createStreamedResponse($writer);
                    $dispositionHeader = $response->headers->makeDisposition(
                        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                        'Bills_' . date("d-m-Y") . '.xls'
                    );
                    $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
                    $response->headers->set('Pragma', 'public');
                    $response->headers->set('Cache-Control', 'maxage=1');
                    $response->headers->set('Content-Disposition', $dispositionHeader);

                return $response;        
                break;
            }
        }
        
        
        return $this->render('DashboardAdminBundle:Money:invoices.html.twig', array("bills" => $bills, "rateBills" => $rateBills, "settings" => $settings));
    }
}


