<?php

namespace Laracasts\Matryoshka\Middlewares;

use Blade;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Laracasts\Matryoshka\BladeDirective;
use Laracasts\Matryoshka\CacheContent;

/**
 * Class MatryoshkaCache
 * @package Laracasts\Matryoshka\Middlewares
 */
class MatryoshkaCache
{
    /**
     * @var RussianCaching
     */
    protected $directive;

    /**
     * @var string
     */
    protected $responseViewName;

    /**
     * @var array
     */
    protected $viewSignatures = [];

    /**
     * MatryoshkaCache constructor.
     * @param BladeDirective $cacheService
     */
    public function __construct(BladeDirective $bladeDirective)
    {
        $this->directive = $bladeDirective;
    }

    /**
     * @param Request $request
     * @param \Closure $next
     * @param mixed ...$args
     * @return Response
     */
    public function handle(Request $request, \Closure $next, ...$args): Response
    {
        if ($request->getMethod() == Request::METHOD_GET && !empty($args) && config('matryoshka.cache_views')) {
            $this->setUpViewNameAndSignatures($args[1]);

            $attributes = [];
            foreach ($this->viewSignatures as $viewSignature) {
                $name = $this->buildNameFromArgumentItem($args[0], $viewSignature, $request);
                if ($this->directive->hasView($name)) {
                    $attributes[$viewSignature] = $this->directive->getContent($name);
                }
            }

            if (!empty($attributes)) {
                return \response(view($this->responseViewName, $attributes));
            }
        }
        return $next($request);
    }

    /**
     * @param string $argumnet
     * @param string $signature
     * @param Request $request
     * @return mixed|string
     */
    public function buildNameFromArgumentItem($argument, $signature, $request)
    {
        $argArray = explode(':', $argument, 2);
        if (count($argArray) == 2) {
            return $argArray[0] . '-' . $request->route($argArray[1]) . '-' . $signature;
        }
        return $argArray[0] . '-' . $signature;
    }

    /**
     * @param $arguments
     */
    public function setUpViewNameAndSignatures($arguments)
    {
        if (empty($arguments)) {
            return;
        }

        $argArray = explode(':', $arguments);
        if (count($argArray) < 2) {
            return;
        }
        $this->responseViewName = array_shift($argArray);
        $this->viewSignatures = $argArray;
    }
}
