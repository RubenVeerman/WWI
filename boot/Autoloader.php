<?php
/**
 * Class autoloader
 * loading php classes
 * @since 30-03-2017
 * @version 1.0
 * @author R Haan
 */
class Autoloader
{
    protected $directories = [];

    private function loadClass($class)
    {
        try
        {
            if ($class[0] == "\\") 
            {
                $class = substr($class, 1);
            }

            $class = str_replace(['\\', '_'], '/', $class).'.php';
            //echo getcwd(); die;
            //include $class when namespace contains directory

            foreach ($this->directories as $directory) 
            {
                // namespace doesn't contain directory ||
                if ( file_exists($path = "{$directory}/{$class}") ) 
                {
                    require_once $path;
                    return true;
                }
                else
                {
                    throw new \Exception("'wwialr001': cannot find file {$path}");                
                }
            }
        }
        catch(Exception $ex)
        {
            $this->HandleUnexpectedException($ex);
        }
    }

    public function register()
    {
        try
        {
            spl_autoload_extensions('.php');
            spl_autoload_register( [$this, 'loadClass'] );
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function addDirectories($dir)
    {
        $this->directories = (array)$dir;
    }

    public function HandleUnexpectedException(Exception $ex)
    {
        App\Helpers\LogHelper::WriteToLogFile("{$ex->getMessage()}\n{$ex->getTraceAsString()}\n\n");
    }

}
?>