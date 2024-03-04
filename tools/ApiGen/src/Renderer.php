<?php declare(strict_types = 1);

namespace ApiGen;

use ApiGen\Index\Index;
use Symfony\Component\Console\Helper\ProgressBar;


interface Renderer
{
	public function render(ProgressBar $progressBar, Index $index): void;
}
