<?php
namespace IngeniousWeb\Skeleton\Services\Utils;

class Hash
{
    public static function make($string)
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public static function check($string, $hash)
    {
        return password_verify($string, $hash);
	}
	
	public function createCode($string)
    {
		$crypt = $this->cryptScript($string, 10);
        $subCrypt = substr($crypt, 8, 64);
		$newCrypt = preg_replace('~/~', '.', $subCrypt);
		return $cryptArray = (object) [
			'salted' => $crypt,
			'unsalted' => $newCrypt
		];
    }

    public function cryptScript($input, $rounds = 8)
    {
        $salt = '';
        $salt_chars = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'), ['.']);
        for ($i=0; $i < 32; $i++) {
            $salt .= $salt_chars[array_rand($salt_chars)];
        }
        return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);
    }

    public function unique()
    {
        $string = random_bytes(32);
		return $crypt = (object) ['unique' => $this->cryptScript($string, 10)];
    }

    public function hashUserCode($data)
    {
        return hash('sha256', $data);
    }
}
