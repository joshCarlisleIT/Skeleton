<?php
namespace IngeniousWeb\Skeleton\Services\Utils;

class Format
{
    public static function escape($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }

    public static function getAge($DOB)
    {
        $birthdate = date('d-m-Y', strtotime(str_replace('/', '-', $DOB)));
        $birthDate = $birthdate;
        //explode the date to get month, day and year
        $birthDate = explode("-", $birthDate);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
        ;
        return $age;
    }
    
    public static function parseBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array("B", "KB", "MB", "GB", "TB");

        return round(pow(1024, $base - floor($base)), $precision).' '. $suffixes[floor($base)];
    }
    
    public static function parsePara($data)
    {
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $reg_email = "/[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+\.([a-zA-Z0-9_-]+)+\.([a-zA-Z0-9_-]+)/";
        $result = str_replace(array("\r\n", "\r", "\n"), "<br />", $data);
        $text = $result;
        if (preg_match($reg_exUrl, $text, $url)) {
            $bio = preg_replace($reg_exUrl, "<a class='blue-text' href='{$url[0]}'>{$url[0]}</a> ", $text);
            if (preg_match($reg_email, $bio, $email)) {
                return preg_replace($reg_email, "<a class='blue-text'>{$email[0]}</a>", $bio);
            } else {
                return $bio;
            }
        } else {
            return $text;
        }
    }
    
    public static function iterate($object)
    {
        foreach ($object as $key => $item) {
            return $item;
        }
	}
	
    public static function getLastUrl()
    {
        $url = 'http:/'.$_SERVER['REQUEST_URI'];
        $url_parts = explode('/', $url);
        return $url_parts[count($url_parts) - 1];
    }

    public static function trim($data)
    {
        $clean = str_replace(array("\r\n", "\r", "\n", " " , "~/~", "/[0-9]+/"), '', $data);
        return $clean;
	}
	
	public static function trimTitle($data)
	{
		$newData = substr($data, 40);
		return $title = substr($newData, 0, -10);
	}
}
