<?php

namespace AppBundle\Command;

use AppBundle\Caller\CurrencyCaller;
use AppBundle\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateCurrenciesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('currency:currencies:populate')
            ->setDescription('Fill/Update the BDD with the currencies available');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var CurrencyCaller $currencyCaller */
        $currencyCaller = $this->getContainer()->get('app.caller.list_currencies');

        $currencies = $currencyCaller->getCurrencies();
        if (false === $currencies) {
            throw new \Exception('An error occured while trying to fetch the currencies.');
        }

        $em                 = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $currencyRepository = $this->getContainer()->get('app.repository.currency');

        $output->writeln('<info>Start fetching the currencies. (' . count($currencies) . ' found)</info>');
        $nbCurrenciesSkipped  = 0;
        $nbCurrenciesInserted = 0;

        foreach ($currencies as $code => $name) {
            $existingCurrencyEntity = $currencyRepository->findOneBy([
                'code' => $code,
            ]);

            if (!($existingCurrencyEntity instanceof Currency)) {
                $newCurrencyEntity = new Currency();
                $newCurrencyEntity->setCode($code);
                $newCurrencyEntity->setName($name);

                $em->persist($newCurrencyEntity);
                $nbCurrenciesInserted++;
            } else {
                $nbCurrenciesSkipped++;
            }
        }

        $em->flush();

        $output->writeln('<info>Finished.</info>');
        $output->writeln('<info>    - ' . $nbCurrenciesInserted . ' Inserted</info>');
        $output->writeln('<info>    - ' . $nbCurrenciesSkipped . ' Skipped</info>');
    }
}
