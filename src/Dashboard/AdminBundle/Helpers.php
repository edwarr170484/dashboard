<?php

namespace Dashboard\AdminBundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Helpers
{
    public function translit($string)
    {
        $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '',    'ы' => 'y',   'ъ' => '',
        '/' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        ' ' => '_', ',' => ''
        );
        return strtr($string, $converter);
    }
    
    public function checkCaptcha($response)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    http_build_query(array('secret'  => '6Lc6wCkTAAAAAH1glqiQycvfoR21pMHgLPqD_zOZ',
                                           'response' => $response)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $server_output = curl_exec($ch);
        curl_close($ch);
            
        $captcha = json_decode($server_output, true);
            
        if($captcha['success'])
        {
            return true;
        }
        
        return false;
    }
    
    public function paginator($start, $total, $items_number, $link , $class = 0, $sort = 0)
    {
        $first_index = 0;
        
        $total_pages = ceil($total/$items_number);
	
        if($class)
            $pages = '<ul class="' . $class . '">';
        else
            $pages = '<ul class="pagination pull-right">';
			
	if($total_pages <= 1)
	{
            $pages.='<li class="active"><a>1</a></li>';
	}
	else
	{
            #необходимо посмотреть на номер страницы, чтобы понять, какие стрелочки выводить
            if($start != 1 && $start != 0)
            {
                if($start == 2)
		{
                    $pages.='<li><a class="prev" href="' . $link . $sort .'"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';
		}
		else
		{
                    $pages.='<li><a class="prev" href="' . $link . '/'.($start-1).$sort.'"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';
		}
            }
				
            //формирую общий массив для всех страниц
            $pagination = array();
				
            for($m = 0; $m < $total_pages;$m++)
            {
                if($m == 0)
                {
                    $pagination[$m] = $link . $sort;
		}
		else
		{
                    $pagination[$m] = $link . '/'.($m+1).$sort;
		}
            }
            //формируем начальное и конечное значение для выборки из этого массива
            if($start > 3)
            {
                $first_index = $start - 4; //индекс первого элемента текущего
                //определяем надо ли сдвигать индекс первого элемента или нет
					
                if(isset($pagination[$start]) && isset($pagination[$start + 1]))
                {
                    $first_index++;
                }
            }
				
            $paginations = array_slice($pagination,$first_index,5);
            $i = $first_index+1;
            if(count($paginations) < 5 && $total_pages > 5)
            {
                $st_i = $first_index - 1;
                $paginations = array_slice($pagination,$st_i,5);
                $i = $first_index;
            }
				
            foreach($paginations as $page_link)
            {
                if($i == $start)
                    $pages.='<li class="active"><a>'.$i.'</a></li>';
                else
                    $pages.='<li><a href="'.$page_link.'">'.$i.'</a></li>';
                $i++;
            }			
            if($start != $total_pages)
                $pages.='<li><a class="next" href="' . $link . '/'.($start+1).$sort.'"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>';	
        }
	$pages.='</ul>';
	return $pages;
    }
}


