<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Helper\FilestructureHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:test',
    description: 'test function',
    aliases: ["app:t"],
    hidden: false
)]
class TestCommand extends Command
{
// TODO: this command has to be run weekly

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
     /*
      * curl request
      * method post
      * meerdere headers
      *     'accept: application/ld+json'
      *     'Content-Type: multipart/form-data'
      * multipart mime data (form)
      *     image=@u mad.jpeg;type=image/jpeg'
      */
    }


}