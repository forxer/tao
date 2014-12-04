<?php
namespace Tao;

use Monolog\ErrorHandler;
use Patchwork\Utf8\Bootup as Utf8Bootup;
use Pimple\Container;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Tao\Controller\Controller;
use Tao\Provider\FilesystemServiceProvider;
use Tao\Provider\FinderServiceProvider;
use Tao\Provider\HttpServiceProvider;
use Tao\Provider\LoggerServiceProvider;
use Tao\Provider\MessagesServiceProvider;
use Tao\Provider\RouterServiceProvider;
use Tao\Provider\SupportServiceProvider;
use Tao\Provider\TemplatingServiceProvider;
use Tao\Provider\TriggersServiceProvider;
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler as WhoopsHandler;

abstract class Application extends Container
{
    public $utilities;

    public $class;

    protected static $models;

    /**
     * Application constructor.
     *
     * @param object $loader The autoloader instance.
     * @param array $config The custom configuration of the application.
     * @param string $appPath The application absolute path.
     * @param array $classesMap The custom classes map of the application.
     */
    public function __construct($loader, array $config = [], $appPath = null, array $classesMap = [])
    {
        # Utilities
        $this->utilities = new Utilities($this);

        # Register start time
        $this->utilities->registerStartTime();

        # Store application path
        $this->utilities->setApplicationPath($appPath);

        # Store classes map
        $this['class'] = $this->utilities->setClassesMap($classesMap);

        # Call container constructor
        parent::__construct($this->utilities->setConfiguration($config));

        # Register core services providers
        $this->register(new FilesystemServiceProvider());
        $this->register(new FinderServiceProvider());
        $this->register(new HttpServiceProvider());
        $this->register(new LoggerServiceProvider());
        $this->register(new MessagesServiceProvider());
        $this->register(new RouterServiceProvider());
        $this->register(new SupportServiceProvider());
        $this->register(new TemplatingServiceProvider());
        $this->register(new TriggersServiceProvider());

        # Enables the portablity layer and configures PHP for UTF-8
        Utf8Bootup::initAll();

        # Redirects to an UTF-8 encoded URL if it's not already the case
        Utf8Bootup::filterRequestUri();

        # Normalizes HTTP inputs to UTF-8 NFC
        Utf8Bootup::filterRequestInputs();

        # Print errors in debug mode
        if ($this['debug'])
        {
            $whoops = new WhoopsRun;
            $whoops->pushHandler(new WhoopsHandler);
            $whoops->register();
        }
        # otherwise log them
        else {
            ErrorHandler::register($this['phpLogger']);
        }

        # Only enable Kint in debug mode
        \Kint::enabled($this['debug']);
    }

    /**
     * Run the application.
     *
     */
    public function run()
    {
        try
        {
            $this['triggers']->callTrigger('before-match-request', $this);

            $this['request']->attributes->add(
                $this['router']->matchRequest($this['request'])
            );

            $this['triggers']->callTrigger('before-controller-resolver', $this);

            $this['response'] = $this->controllerResolver();
        }
        catch (ResourceNotFoundException $e)
        {
            $this['response'] = (new Controller($this))->serve404();
        }
        catch (\Exception $e)
        {
            $this['response'] = new Response();
            $this['response']->headers->set('Content-Type', 'text/plain');
            $this['response']->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $this['response']->setContent($e->getMessage());
        }

        if ($this['x-frame-options']) {
            $this['response']->headers->set('x-frame-options', $this['x-frame-options']);
        }

        $this['response']->prepare($this['request']);

        $this['triggers']->callTrigger('before-send-response', $this);

        $this['response']->send();
    }

    protected function controllerResolver()
    {
        if (!$controller = $this['request']->attributes->get('_controller')) {
            throw new \RuntimeException('Unable to look for the controller as the "controller" parameter is missing');
        }

        if (false === strpos($controller, '::')) {
            throw new \RuntimeException(sprintf('The controller "%s" does not follow Tao coding standards : "class::method".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        if ($this['routing.controllers_namespace']) {
            $class = $this['routing.controllers_namespace'] . '\\' . $class;
        }

        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Class "%s" does not exist.', $class));
        }

        $callable = [
            new $class($this),
            $method
        ];

        if (!is_callable($callable)) {
            throw new \RuntimeException(sprintf('The controller for URI "%s" is not callable.', $this['request']->getPathInfo()));
        }

        $this['request']->attributes->set('controller_class', $class);
        $this['request']->attributes->set('controller_method', $method);

        return call_user_func($callable);
    }

    /**
     * Return the instance of specified model.
     *
     * @param string $sModel
     * @return \Tao\Database\Model
     */
    public function getModel($sModel)
    {
        $namespacedClass = $this['database.models_namespace'] . '\\' . $sModel;

        if (!isset(static::$models[$sModel])) {
            static::$models[$sModel] = new $namespacedClass($this);
        }

        return static::$models[$sModel];
    }
}
