<?php

namespace Laracasts\Matryoshka;

use Exception;

/**
 * Class CacheContent
 * @package Laracasts\Matryoshka
 */
class CacheContent
{
    /**
     * The content pulled from cache.
     *
     * @var string
     */
    protected $content;

    /**
     * A Name of the cahched content.
     *
     * @param string $name
     */
    protected $name;

    /**
     * CacheContent constructor.
     * @param string $name
     * @param string $content
     */
    public function __construct(string $name, string $content)
    {
        $this->name = $name;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param $model
     * @param string $suffix
     * @param string $preffix
     * @return string
     */
    public static function buildViewNameForEntity($model, $suffix, $preffix)
    {
        return $model instanceof CacheContent ? $model->getName() : $suffix . '-' . $model->id . '-' . $preffix;
    }
}
