<?php

declare(strict_types = 1);

namespace CodeLts\CliTools\ErrorFormatter;

use CodeLts\CliTools\AnalysisResult;
use CodeLts\CliTools\Output;

class RawTextErrorFormatter implements ErrorFormatter
{

    public function formatErrors(
        AnalysisResult $analysisResult,
        Output $output
    ): int {
        foreach ($analysisResult->getNotFileSpecificErrors() as $notFileSpecificError) {
            $output->writeFormatted('<fg=red>ERROR</>: ');
            $output->writeLineFormatted($notFileSpecificError);
        }

        foreach ($analysisResult->getFileSpecificErrors() as $error) {
            $output->writeFormatted('<fg=red>ERROR</>: ');
            $output->writeLineFormatted(
                sprintf('%s in %s:%d', $error->getMessage(), $error->getFile() ?? '', $error->getLine())
            );
        }

        foreach ($analysisResult->getWarnings() as $warning) {
            $output->writeFormatted('<fg=yellow>WARNING</>: ');
            $output->writeLineFormatted($warning);
        }


        return $analysisResult->hasErrors() ? 1 : 0;
    }

}
