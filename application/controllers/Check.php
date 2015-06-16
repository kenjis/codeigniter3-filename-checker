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
			'controllers',
			'libraries',
			'models',
			'core',
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
			new RecursiveDirectoryIterator(APPPATH . $dir),
			'/\A.+\.php\z/i',
			RecursiveRegexIterator::GET_MATCH
		);

		foreach(new RecursiveIteratorIterator($iterator) as $file)
		{
			$filename = preg_replace(
				'/'.preg_quote(APPPATH, '/').'/', 'APPPATH/', $file[0]
			);
			
			if (! $this->checkFilename($filename, $dir))
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

	private function checkFilename($filepath, $dir)
	{
		$filename = basename($filepath);
		
		if ($dir === 'libraries' || $dir === 'core')
		{
			$prefix = config_item('subclass_prefix');
			
			if ($this->hasPrefix($filename, $prefix))
			{
				$name = substr($filename, strlen($prefix));
				
				return $this->checkUcfirst($name);
			}
		}
		
		return $this->checkUcfirst($filename);
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
