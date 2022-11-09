<?php
declare(strict_types=1);

namespace App\Command\Helper;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class FilestructureHelper
{
    private Package $package;

    public function __construct()
    {
        $this->package = new Package(new EmptyVersionStrategy());
    }

    public function getGamePaths(): array
    {
        $arrFiles = [];
        $handle = opendir('assets/steam/games');

        if ($handle) {
            while (($entry = readdir($handle)) !== FALSE) {
                if($entry !== "." && $entry !== "..") {
                    $arrFiles[] = "assets/steam/games/{$entry}";
                }
            }
        }
        closedir($handle);
        sort($arrFiles);

        return $arrFiles;
    }

    public function getAppIds(): array
    {
        $arrFiles = [];
        $handle = opendir('assets/steam/games');

        if ($handle) {
            while (($entry = readdir($handle)) !== FALSE) {
                if($entry !== "." && $entry !== "..") {
                    $arrFiles[] = $entry;
                }
            }
        }
        closedir($handle);
        sort($arrFiles);

        return $arrFiles;
    }
}