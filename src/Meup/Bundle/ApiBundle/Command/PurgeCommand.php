<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Command;

use Meup\Bundle\ApiBundle\Manager\SkuManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PurgeCommand
 *
 * Delete sku records matching with criteria.
 * Usage: php app/console meup:kali:purge <project> [<type>]
 *
 * @author Florian AJIR <florian@1001pharmacies.com>
 */
class PurgeCommand extends Command
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var SkuManager
     */
    private $manager;

    /**
     * @var int
     */
    private $count;

    /**
     * @var array
     */
    private $criteria;

    /**
     * @param SkuManager $manager
     */
    public function __construct(SkuManager $manager)
    {
        $this->manager = $manager;

        parent::__construct();
    }

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
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Execute command without confirmation.'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->criteria = $this->buildCriteria();
        $this->count = $this->manager->count($this->criteria);
        $this->writeSearchCriteria();
        $this->writeCount();
        if ($this->count && $this->confirm()) {
            $this->doExecute();
        }
    }

    /**
     * @return bool
     */
    private function confirm()
    {
        return $this->input->getOption('force') || $this->askConfirm();
    }

    private function askConfirm()
    {
        $this->output->writeln(
            '<comment>This command will remove %u sku from database, no rollback possible.</comment>'
        );
        $dialog = $this->getHelper('dialog');

        return $dialog->askConfirmation(
            $this->output,
            "<question>Do you confirm? (y/n)</question>\n",
            false
        );
    }

    private function doExecute()
    {
        $this->manager->deleteWhere($this->criteria);
    }

    /**
     * @return array
     */
    private function buildCriteria()
    {
        $criteria = array();
        if ($this->input->hasArgument('project')) {
            $criteria['project'] = $this->input->getArgument('project');
        }
        if ($this->input->hasArgument('type')) {
            $criteria['type'] = $this->input->getArgument('type');
        }

        return $criteria;
    }

    private function writeSearchCriteria()
    {
        $searching = "Searching for ";
        if ($this->input->hasArgument('project')) {
            $searching .= 'project=' . $this->input->getArgument('project');
        }
        if ($this->input->hasArgument('type')) {
            $searching .= 'and type=' . $this->input->getArgument('type');
        }
        $this->output->writeln($searching);
    }

    /**
     * Write how many matching records found
     */
    private function writeCount()
    {
        $this->output->writeln($this->count . ' sku found in database.');
    }
}
