<?php

namespace Caldera\GeoBundle\Command;

use Caldera\GeoBundle\Entity\Track;
use Caldera\GeoBundle\GpxReader\TrackReader;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePolylinesCommand extends ContainerAwareCommand
{
    /**
     * @var Registry $doctrine
     */
    protected $doctrine;

    /**
     * @var EntityManager $manager
     */
    protected $manager;

    /**
     * @var TrackReader $trackReader
     */
    protected $trackReader;

    protected function configure(): void
    {
        $this
            ->setName('geobundle:track:generate-polylines')
            ->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->doctrine = $this->getContainer()->get('doctrine');
        $this->manager = $this->doctrine->getManager();
        $this->trackReader = $this->getContainer()->get('caldera.geobundle.reader.track');

        $trackClass = $this->getContainer()->getParameter('caldera.geo_bundle.track_class');

        $tracks = $this->doctrine->getRepository($trackClass)->findAll();

        /** @var Track $track */
        foreach ($tracks as $track) {
            try {
                $this->trackReader->loadTrack($track);

                $output->writeln(sprintf('<info>Generated polylines for track #%d</info>', $track->getId()));
            } catch (\Exception $exception) {
                $output->writeln(sprintf('<error>Could not load track #%d</error>', $track->getId()));
            }
        }

        $this->manager->flush();
    }


}