<?php
namespace Framework\ModuleManager;

class ModuleManager
{
    /**
     * @var array
     */
    private $modulePaths = [];

    /**
     * ModuleManager constructor.
     * @param array $modules
     * @throws \Exception
     */
    public function __construct(array $modules)
    {
        foreach ($modules as $module) {
            $path = APP_BASE_PATH . DS . 'module' . DS . $module;

            if (!is_dir($path)) {
                throw new \Exception('Module ' . $module . ' path is not defined');
            }

            $this->modulePaths[$module] = $path;
        }
    }

    /**
     * @return array
     */
    public function getModulePaths()
    {
       return $this->modulePaths;
    }
}