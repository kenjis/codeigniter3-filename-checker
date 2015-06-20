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
	private $fix = FALSE;

	public function __construct()
	{
		parent::__construct();
		
		$this->dir = array(
			'controllers',
			'libraries',
			'models',
			'core',
		);
	}

	public function filename($fix = 'no')
	{
		if ($fix === 'fix')
		{
			$this->fix = TRUE;
		}
		
		foreach ($this->dir as $dir)
		{
			$this->recursiveCheckFilename($dir);
		}
		
		$this->display();
	}

	private function recursiveCheckFilename($dir)
	{
		$iterator = new RecursiveRegexIterator(
			new RecursiveDirectoryIterator(APPPATH . $dir),
			'/\A.+\.php\z/i',
			RecursiveRegexIterator::GET_MATCH
		);

		foreach(new RecursiveIteratorIterator($iterator) as $file)
		{
			$filename = $file[0];
			
			$filename_show = preg_replace(
				'/'.preg_quote(APPPATH, '/').'/', 'APPPATH/', $file[0]
			);
			
			if (! $this->checkFilename($filename, $dir))
			{
				$this->output('Error: ' . $filename_show);
			}
			else
			{
				$this->output('Okay: ' . $filename_show);
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

	private function checkFilename($filepath, $dir)
	{
		$filename = basename($filepath);
		
		if ($dir === 'libraries' || $dir === 'core')
		{
			$prefix = config_item('subclass_prefix');
			
			if ($this->hasPrefix($filename, $prefix))
			{
				$filename = substr($filename, strlen($prefix));
			}
		}
		
		if (! $this->checkUcfirst($filename))
		{
			if ($this->fix)
			{
				$newname = dirname($filepath).'/'.ucfirst($filename);
				if (rename($filepath, $newname))
				{
					$this->output('Rename: ' . $filepath . PHP_EOL . '     -> ' . $newname);
				}
			}
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	private function checkUcfirst($filename)
	{
		if (ucfirst($filename) !== $filename)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	private function hasPrefix($filename, $prefix)
	{
		if (strncmp($prefix, $filename, strlen($prefix)) === 0)
		{
			return TRUE;
		}
		
		return FALSE;
	}
}
