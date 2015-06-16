<?php
/**
 * Part of CodeIgniter3 Filename Checker
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter3-filename-checker
 */

class Check extends CI_Controller
{
	private $dir;
	private $output_ = array();

	public function __construct()
	{
		parent::__construct();
		
		$this->dir = array(
			APPPATH . 'controllers',
			APPPATH . 'libraries',
			APPPATH . 'models',
		);
	}

	public function filename()
	{
		foreach ($this->dir as $dir)
		{
			$this->recursiveCheckFilename($dir);
		}
		
		$this->display();
	}

	private function recursiveCheckFilename($dir)
	{
		$iterator = new RecursiveRegexIterator(
			new RecursiveDirectoryIterator($dir),
			'/\A.+\.php\z/i',
			RecursiveRegexIterator::GET_MATCH
		);

		foreach(new RecursiveIteratorIterator($iterator) as $file)
		{
			$filename = preg_replace(
				'/'.preg_quote(APPPATH, '/').'/', 'APPPATH/', $file[0]
			);
			
			if (! $this->checkFilename($filename))
			{
				$this->output('Error: ' . $filename);
			}
			else
			{
				$this->output('Okay: ' . $filename);
			}
		}
	}

	private function output($line)
	{
		$this->output_[] = $line . PHP_EOL;
	}

	private function display()
	{
		sort($this->output_);
		
		if (! is_cli())
		{
			echo "<pre>\n";
		}
		
		foreach ($this->output_ as $line)
		{
			echo $line;
		}
		
		if (! is_cli())
		{
			echo "</pre>\n";
		}
	}

	private function checkFilename($filepath)
	{
		$filename = basename($filepath);
		if (ucfirst($filename) !== $filename)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}
