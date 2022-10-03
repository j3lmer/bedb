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
        return glob("{$this->package->getUrl("assets/steam/games/**")}");
    }

    public function getAppIds(): array
    {
        $appids = [];

        foreach ($this->getGamePaths() as $path) {
            $appids[] = explode("assets/steam/games/", $path);
        }
        return $appids;
    }
}