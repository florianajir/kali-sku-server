<?php
namespace Meup\Bundle\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PurgeCommand
 *
 * php app/console meup:kali:purge <project> [<type>]
 *
 * @author Florian AJIR <florian@1001pharmacies.com>
 */
class PurgeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('meup:kali:purge')
            ->setDescription("Delete sku records matching with criteria.")
            ->addArgument(
                'project',
                InputArgument::REQUIRED,
                'The project name.'
            )
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'The object type.'
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $input->getArgument('project');
        $type = $input->getArgument('type');
        $searching = "Searching for ";
        $searching .= null === $type
            ? "project=\"$project\""
            : "project=\"$project\" and type=\"$type\"";
        $output->writeln($searching);
        $count = $this->countMatchingRows($project, $type);
        if ($count) {
            $found = $count > 1
                ? "$count results found."
                : "1 result found.";
            $output->writeln($found);
            $dialog = $this->getHelper('dialog');
            if (!$dialog->askConfirmation(
                $output,
                '<question>This will delete IRREMEDIABLY this sku, CONTINUE ?</question>',
                false
            )
            ) {
                $output->writeln("Action canceled.");
            } else {
                $this->execDeleteQuery($project, $type);
                $output->writeln("$count sku removed.");
            }
        } else {
            $output->writeln("No matching records.");
        }
        $output->writeln("Exit.");
    }

    /**
     * @param string $project
     * @param string $type
     *
     * @return mixed
     */
    private function countMatchingRows($project, $type)
    {
        $qb = $this->getSkuQueryBuilder();
        $qb
            ->select('count(s)')
            ->where('s.project = :project')
            ->setParameter(':project', $project);
        if (!is_null($type)) {
            $qb
                ->andWhere('s.foreignType = :type')
                ->setParameter(':type', $type);
        }

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getSkuQueryBuilder()
    {
        $repository = $this
            ->getEntityManager()
            ->getRepository('MeupApiBundle:Sku');

        return $repository->createQueryBuilder('s');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @param string $project
     * @param string $type
     */
    private function execDeleteQuery($project, $type)
    {
        $qb = $this->getSkuQueryBuilder();
        $qb
            ->delete()
            ->where('s.project = :project')
            ->setParameter(':project', $project);
        if (!is_null($type)) {
            $qb
                ->andWhere('s.foreignType = :type')
                ->setParameter(':type', $type);
        }
        $qb
            ->getQuery()
            ->execute();
    }
}
