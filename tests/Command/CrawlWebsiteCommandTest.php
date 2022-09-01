<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CrawlWebsiteCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $command = $application->find('app:crawl-website');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            // pass arguments to the helper
            'website' => 'Wirelss',
            'sortBy' => 'ASC',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Found 6 package(s).', $output);
        $this->assertStringContainsString('Job done!', $output);
        $commandTester->assertCommandIsSuccessful();

        // further tests
    }
}
