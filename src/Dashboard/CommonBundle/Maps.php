<?php

namespace Dashboard\CommonBundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Maps
{
    public function getCoordinatesByAddress($address, $key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . ",+18&key=" . $key);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $server_output = curl_exec($ch);
        curl_close($ch);
            
        $result = json_decode($server_output, true);
            
        return $result;
    }
}


