<?php

namespace App\Traits;

trait RandomUsernameTrait
{
    // USERNAME GENERATE
    public static function generateUsername(string $nama, string $tglDaftar, int $pos = 6): string
    {
        // Remove special characters from $nama
        $nama = preg_replace('/[^a-zA-Z0-9]/', '', $nama);

        $trimName = strtoupper(str_replace(' ', '', $nama));
        $stringLength = strlen($trimName);

        // Check if $pos is within the bounds of the name length
        if ($pos <= $stringLength) {
            $subStrName = substr($trimName, -$pos, 6);
        }

        // If $pos is greater than the name length
        if ($pos > $stringLength) {
            if ($stringLength < 3) {
                $countRandomStr = (6 - $stringLength);
                $subStrName = substr($trimName, 0);
                $subStrName = $subStrName.self::getRandomString($countRandomStr);
            } else {
                $subStrName = substr($trimName, 0, 3);
                $subStrName = $subStrName.self::getRandomString(3);
            }
        }

        $tanggal = date('d', strtotime($tglDaftar));
        $tahun = date('y', strtotime($tglDaftar));
        $username = $subStrName.$tanggal.$tahun;

        return self::checkUsername($nama, $tglDaftar, $pos, $username);
    }

    private static function getRandomString(int $length): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    private static function checkUsername(string $nama, string $tglDaftar, int $pos, string $username): string
    {
        while (self::where('username', $username)->exists()) {
            $pos = $pos + 1;
            $username = self::generateUsername($nama, $tglDaftar, $pos);
        }

        return $username;
    }
}
