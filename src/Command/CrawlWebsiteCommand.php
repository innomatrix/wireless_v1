<?php

namespace App\Command;

use App\Entity\Package;
use App\Scraper\Wireless;
use Error;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;

class CrawlWebsiteCommand extends Command
{
    protected static $defaultName = 'app:crawl-website';
    protected $website = 'Wireless';
    protected $defaultWebsite = 'Wireless';

    private $allowedSortBy = ['ASC', 'DESC'];
    private $availableWebsites = ['Wireless'];
    private $sortBy = 'DESC'; // default

    protected function configure()
    {
        $this->setName('crawl-for-wireless')
            ->setDescription('Crawles Website (default: ' . $this->defaultWebsite . ') page and returns JSON array with packages prices sorted by default in ' . $this->sortBy . ' order')
            ->setHelp('Optional parameter --sortBy with accepted values ASC or DESC.')
            ->addArgument('website', InputArgument::OPTIONAL, 'Pass the website.')
            ->addArgument('sortBy', InputArgument::OPTIONAL, 'Pass the sortBy.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {

            // check if website param is defined
            if (null !== $input->getArgument('website')) {
                $websiteArgument = $input->getArgument('website');
                if (in_array($websiteArgument, $this->availableWebsites))
                    $this->website = $websiteArgument;
                else $this->website = $this->defaultWebsite;
            } else $this->website = $this->defaultWebsite;

            // load contract interface
            $class = 'App\Scraper\\' . $this->website;
            $websiteContract = new $class();

            // check if sortBy param is defined
            if (null !== $input->getArgument('sortBy')) {
                $sortByArgument = $input->getArgument('sortBy');
                if (in_array($sortByArgument, $this->allowedSortBy))
                    $this->sortBy = $sortByArgument;
                else throw new Error(sprintf('<error>Seems like provided "SortBy"-> <info> %s </info> <- value is not allowed!</error>', $sortByArgument));
            }

            // Say hello and start the scraping
            $progressBar = new ProgressBar($output, 3);
            $progressBar->start();
            $output->writeln(PHP_EOL . sprintf('<info>Hello!, trying to fetch <href="' . $websiteContract->getName() . '">' . $websiteContract->getUrl() . '</></info>'));

            // prepare Panther headless browser
            $client = Client::createChromeClient();
            $crawler = new Crawler();

            // fetch the source
            $crawler = $client->request('GET', $websiteContract->getUrl());

            // $client->takeScreenshot(__DIR__ . '/screen.png');
            $crawler = $client->waitForVisibility($websiteContract->getWrapperSelector());

            $packagesCount = $crawler->filter($websiteContract->getPackageWrapperSelector())->count();

            $progressBar->advance();
            $output->writeln(sprintf(PHP_EOL . '<info>Found %s package(s). Extraction in progress...</info>', $packagesCount));
            $collection = [];

            $crawler->filter('#subscriptions')->each(
                function (Crawler $node, $i) use (&$collection, $websiteContract) {
                    // $option
                    $annaualOrMonthly = $node->filter('h2')->text();

                    $node->filter($websiteContract->getPackageWrapperSelector())->each(
                        function (Crawler $node, $i) use ($annaualOrMonthly, &$collection, $websiteContract) {
                            $package = new Package();

                            $package->setOption($websiteContract->extractor($annaualOrMonthly, 'SUBSCRIPTION_OPTION'));
                            $package->setTitle($node->filter($websiteContract->getHeaderSelector())->text());
                            $package->setTier($websiteContract->extractor($package->getTitle(), 'PACKAGES_NAMES'));
                            // print_r('Package option: '.$package->getOption() . ' Title: ' .$package->getTitle() . ' Tier: ' .$package->getTier(). PHP_EOL);

                            $package->setDescription($node->filter($websiteContract->getDescSelector())->text());
                            $package->setPrice($node->filter($websiteContract->getPriceSelector())->text());
                            $package->setMonthlyPrice();

                            // we group packages by planType and flag the monthly/annual indicator
                            $collection[$package->getTier()][$package->getOption()] = $package;
                        }
                    );
                }
            );

            // Move one to prepare results
            $output->writeln(sprintf('<info>Extraction completed, working with packages!</info>'));
            $progressBar->advance();
            // this will be our result JSON array
            $result = [];

            foreach ($websiteContract::PACKAGES_NAMES as $name => $commonTier) {
                foreach ($collection[$commonTier] as $planType => $package) {
                    if ($planType == $websiteContract::SUBSCRIPTION_OPTION['Monthly'])
                        $monthlyPackage = $package;
                    if ($planType == $websiteContract::SUBSCRIPTION_OPTION['Annual'])
                        $annaualPackage = $package;

                    if (empty($result))
                        $result[] = $package;
                    else {
                        if ($result[0]->getMonthlyPrice() > $package->getMonthlyPrice())
                            array_push($result, $package);
                        else array_unshift($result, $package);
                    }
                }
                $monthlyPackage->setDiscount(0);
                $annaualPackage->calculateAnnualDiscount($monthlyPackage);
            }

            // in case we want to sort ASC
            if($this->sortBy == "ASC")
                $result = array_reverse($result);

            foreach ($result as $package)
                print_r($package->jsonSerialize());

            // Finishing
            $progressBar->advance();
            $output->writeln(PHP_EOL . sprintf('<info>Job done!</info>') . PHP_EOL);           
        } catch (\Throwable $th) {
            $output->writeln($th->getMessage());
            return Command::FAILURE;
        }

        // End
        $progressBar->finish();
        return Command::SUCCESS;
    }
}
