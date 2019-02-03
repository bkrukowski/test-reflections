<?php

declare(strict_types=1);

/*
 * This file is part of the php-header-file/php-header-file package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpHeaderFile\Commands;

use PhpHeaderFile\Exporters\CodeExporter;
use PhpHeaderFile\Exporters\CodeExporterDecorator;
use PhpHeaderFile\Exporters\ExporterInterface;
use PhpHeaderFile\Pattern\PatternCompiler;
use PhpHeaderFile\View\TwigFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

final class ExportCommand extends Command
{
    /**
     * @internal
     */
    public const COMMAND_NAME = 'export';

    protected function configure()
    {
        parent::configure();
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Exports definitions of functions/classes/constants/extensions')
            ->addArgument('patterns', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Things to be exported e.g. "ext-pdo", available prefixes: fn-, class-, ext-, const-')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Name of file where php code will be written', 'headers.php')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite if file exists')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputFile = $input->getOption('output');
        $force = $input->getOption('force');

        $fs = new Filesystem();
        $fileExists = $fs->exists($outputFile);

        if (!$force && $fileExists) {
            throw new \RuntimeException(\sprintf('File %s exists, use --force option to overwrite', $outputFile));
        }

        if ($fileExists) {
            $output->writeln(\sprintf('<error>Files %s exists, content will be overwritten</error>', OutputFormatter::escape($outputFile)));
        }

        $exporter = $this->getDecoratedExporter($input->getArgument('patterns'));

        if (!$exporter->hasThingsToExport()) {
            throw new \RuntimeException('Nothing to export');
        }

        if ($output->isVerbose()) {
            $output->writeln('Exported things:');
            foreach ($exporter->listExportedThings() as $item) {
                $output->writeln('  * '.OutputFormatter::escape($item));
            }
        }

        $fs->dumpFile($outputFile, $exporter->export());
        $output->writeln(\sprintf('Successfully exported headers to file %s', OutputFormatter::escape($outputFile)));
    }

    private function getDecoratedExporter(array $patterns): ExporterInterface
    {
        $this->createCodeExporterDecorator()->decorate(
            $exporter = $this->createCodeExporter(),
            ...$patterns
        );

        return $exporter;
    }

    private function createCodeExporter(): CodeExporter
    {
        return new CodeExporter((new TwigFactory())->create());
    }

    private function createCodeExporterDecorator(): CodeExporterDecorator
    {
        return new CodeExporterDecorator(new PatternCompiler());
    }
}
