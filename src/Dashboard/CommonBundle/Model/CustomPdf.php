<?php
namespace Dashboard\CommonBundle\Model;

use TCPDF;

class CustomPdf extends TCPDF {
    private $settings;
    
    public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false, $settings) {
       parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
       $this->settings = $settings;
    }
    
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
	// get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $this->Image('/bundles/images/site/' . $this->settings->getSiteLogo(), 12, 15, 36, 0, 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        $this->SetXY(90, 8);
        $this->SetFont('dejavusans', '', 10);
        $html = '<dl><dt><b>EDELTIME S.L.</b></dt><dt>C / Balmes 211, ppal 20</dt><dt>08006, Barcelona, España<br/></dt><dt><a href="mialto://cpr@auto28.es" dir="ltr">cpr@auto28.es</a></dt><dt><a href="http://auto28.es" dir="ltr">auto28.es</a></dt></dl>';
        $this->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
        $this->SetXY(170, 11);
        $this->Image('/bundles/Default/images/qr-code.png', '', '', 18, 0, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetXY(160, 34);
        $html = '<b>C.I.F</b> B67486928';
        $this->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
    
    public function Footer() {
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        
        $this->SetY(-25);
        $this->SetFont('dejavusans', '', 3);
        $html = '<table><tr><td width="400"> </td><td bgcolor="#F5AA01" width="240"> </td></tr></table>';
        $this->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
        $this->SetFont('dejavusans', '', 9);
        $html = '<div></div><b>Inscrita en el Registro mercantil de Barcelona</b><br/>';
        $this->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
        $html = '<table>'
                . '<tr>'
                . '<td><b>Tomo:</b> 47023</td>'
                . '<td><b>Folio:</b> 134</td>'
                . '<td><b>Hoja:</b> 538518</td>'
                . '<td><b>Sección:</b> General</td>'
                . '<td><b>Inscripción:</b> 1</td>'
                . '<td><b>CNAE:</b> 7311</td>'
                . '</tr>'
                . '</table>';
        $this->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
    }
}

