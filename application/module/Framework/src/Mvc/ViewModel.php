<?php
namespace Framework\Mvc;

class ViewModel
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var string
     */
    private $path;

    /**
     * @param $key
     * @param $value
     */
    public function assign(string $key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * @param string $view
     */
    public function render(string $view)
    {
        $loader   = new \Twig_Loader_Filesystem($this->path);
        $loader->addPath($this->path . DS . '..' . DS . 'layout');

        $twig  = new \Twig_Environment($loader);
        $lexer = new \Twig_Lexer($twig, array(
            'tag_variable' => array('{$', '}'),
        ));

        $twig->setLexer($lexer);
        $template = $twig->load($view);

        echo $template->render($this->parameters);
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}