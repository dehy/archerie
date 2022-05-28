<?php

namespace App\Command;

use App\Scrapper\FftaScrapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[
    AsCommand(
        name: "app:ffta:fetch-profile",
        description: "Fetch and display the profile from the FFTA website"
    )
]
class FftaFetchProfileCommand extends Command
{
    public function __construct(protected readonly FftaScrapper $scrapper)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument("fftaId", InputArgument::REQUIRED, "FFTA ID");
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);
        $fftaId = $input->getArgument("fftaId");

        $identity = $this->scrapper->fetchLicenceeIdentity($fftaId);

        var_dump($identity);

        return Command::SUCCESS;
    }
}