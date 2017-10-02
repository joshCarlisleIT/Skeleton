<?php

namespace Silktide\LazyBoy\Controller;

use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silktide\Syringe\ContainerBuilder;
use Silex\Application;
use Silktide\LazyBoy\Config\RouteLoader;
use Silktide\Syringe\SyringeServiceProvider;

/**
 * FrontController - loads routes, builds and runs the application
 */
class FrontController 
{

    const DEFAULT_APPLICATION_CLASS = "Silex\\Application";

    /**
     * @var ContainerBuilder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $configDir;

    /**
     * @var string
     */
    protected $applicationClass;

    /**
     * @var array
     */
    protected $serviceProviders;

    /**
     * @var string
     */
    protected $routeFilename = "routes.yml";

    /**
     * @param ContainerBuilder $builder
     * @param string $configDir
     * @param string $applicationClass
     * @param array $serviceProviders
     */
    public function __construct(ContainerBuilder $builder, $configDir, $applicationClass, array $serviceProviders = [])
    {
        $this->builder = $builder;
        $this->configDir = $configDir;
        $this->setApplicationClass($applicationClass);
        $this->setProviders($serviceProviders);
    }

    /**
     * @param string $filename
     */
    public function setRouteFilename($filename)
    {
        $this->routeFilename = $filename;
    }

    protected function setApplicationClass($applicationClass) {
        if ($applicationClass != self::DEFAULT_APPLICATION_CLASS && !is_subclass_of($applicationClass, self::DEFAULT_APPLICATION_CLASS)) {
            throw new \InvalidArgumentException(sprintf("The class '%s' is not a subclass of '%s'", $applicationClass, self::DEFAULT_APPLICATION_CLASS));
        }
        $this->applicationClass = $applicationClass;
    }

    protected function setProviders(array $providers)
    {
        $this->serviceProviders = [];
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function addProvider($provider)
    {
        if ($provider instanceof ServiceProviderInterface || $provider instanceof BootableProviderInterface) {
            $this->serviceProviders[] = $provider;
        }
    }

    public function runApplication()
    {
        // create application
        /**
         * @var $application Application
         */
        $application = new $this->applicationClass();

        $application["app"] = function() use ($application) {
            return $application;
        };

        $syringeServiceProviderIncluded = false;
        // register service controller provider
        foreach ($this->serviceProviders as $provider) {
            $application->register($provider);
            if ($provider instanceof SyringeServiceProvider) {
                $syringeServiceProviderIncluded = true;
            }
        }

        if (!$syringeServiceProviderIncluded) {
            $application->register(new SyringeServiceProvider($this->builder));
        }

        // load routes
        /** @var RouteLoader $routeLoader */
        $routeLoader = $application["routeLoader"];
        $routeLoader->parseRoutes($this->configDir . "/" . $this->routeFilename);

        // run the app
        $application->run();
    }

} 