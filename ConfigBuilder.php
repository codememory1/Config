<?php

namespace Codememory\Components\Configuration;

use Codememory\Components\Configuration\Interfaces\ConfigBuilderInterface;
use Codememory\Components\Configuration\Interfaces\ModeInterface;
use Codememory\Components\Markup\Interfaces\MarkupTypeInterface;
use Codememory\Components\Markup\Markup;
use Codememory\FileSystem\File;
use Codememory\FileSystem\Interfaces\FileInterface;
use Codememory\Support\Arr;
use Codememory\Support\Str;

/**
 * Class ConfigBuilder
 *
 * @package Codememory\Components\Configuration
 *
 * @author  Codememory
 */
class ConfigBuilder implements ConfigBuilderInterface
{

    public const KEY_WITH_BINDS = 'binds';

    /**
     * @var Utils
     */
    private Utils $utils;

    /**
     * @var ModeInterface
     */
    private ModeInterface $mode;

    /**
     * @param Utils         $utils
     * @param ModeInterface $mode
     */
    public function __construct(Utils $utils, ModeInterface $mode)
    {

        $this->utils = $utils;
        $this->mode = $mode;

    }

    /**
     * @inheritDoc
     */
    public function getMergedConfigs(MarkupTypeInterface $markupType): array
    {

        $filesystem = new File();
        $markup = new Markup($markupType);
        $configs = [];

        foreach ($this->getPaths($filesystem, false, false) as $path) {
            Arr::merge($configs, $markup->open($path)->get());
        }

        return $configs;

    }

    /**
     * @inheritDoc
     */
    public function getBinds(array $mergedConfigs): array
    {

        $binds = [];

        foreach ($mergedConfigs as $data) {
            if (array_key_exists(self::KEY_WITH_BINDS, $data)) {
                Arr::merge($binds, $data[self::KEY_WITH_BINDS]);
            }
        }

        return $binds;

    }

    /**
     * @return string
     */
    private function getPathWithConfigs(): string
    {

        $pathWithConfigs = trim($this->utils->getPathWithConfigs(), '/');
        $modeSubdirectoryWithConfigs = trim($this->mode->getSubdirectory(), '/');

        return sprintf('%s/%s', $pathWithConfigs, $modeSubdirectoryWithConfigs);

    }

    /**
     * @param FileInterface $filesystem
     * @param bool          $withDir
     * @param bool          $withExtension
     *
     * @return array
     */
    private function getPaths(FileInterface $filesystem, bool $withDir, bool $withExtension): array
    {

        $subdirectoryPaths = [$this->getPathWithConfigs()];
        $paths = [];

        foreach ($this->mode->getModeSubdirectories() as $modeSubdirectory) {
            $subdirectoryPaths[] = sprintf('%s%s', $this->getPathWithConfigs(), trim($modeSubdirectory, '/'));
        }

        foreach ($subdirectoryPaths as $subdirectoryPath) {
            $paths = array_merge($paths, array_map(function (?string $path) use ($subdirectoryPath) {
                return sprintf('%s/%s', trim($subdirectoryPath, '/'), trim($path, '/'));
            }, $filesystem->scanning($subdirectoryPath)));
        }

        foreach ($paths as $index => &$path) {
            if (!$withDir && $filesystem->is->directory($path)) {
                unset($paths[$index]);
            }

            if (!$withExtension) {
                $path = Str::trimAfterSymbol($path, '.', false);
            }
        }

        return $paths;

    }

}