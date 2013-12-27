<?php
/*
 * This file is part of the h4cc/LeezyPheanstalkBundleExtra package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace h4cc\LeezyPheanstalkBundleExtra\Proxy;

use \Pheanstalk_PheanstalkInterface;
use \Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;

/**
 * Class PrefixedTubePheanstalkProxy.
 *
 * Decorating Pheanstalk proxy for adding a prefix to the tube names.
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class PrefixedTubePheanstalkProxy extends PheanstalkProxy
{
    /** @var string */
    protected $tubePrefix = '';

    /**
     * Prefix, that will be used for all tubes going through this proxy.
     *
     * @param string $prefix
     */
    public function setTubePrefix($prefix)
    {
        $this->tubePrefix = (string)$prefix;
    }

    /**
     * {@inheritDoc}
     */
    public function ignore($tube)
    {
        return parent::ignore($this->prefixTube($tube));
    }

    /**
     * {@inheritDoc}
     */
    public function listTubes()
    {
        return $this->unprefixTubes(parent::listTubes());
    }

    /**
     * {@inheritDoc}
     */
    public function listTubesWatched($askServer = false)
    {
        return $this->unprefixTubes(parent::listTubesWatched($askServer));
    }

    /**
     * {@inheritDoc}
     */
    public function listTubeUsed($askServer = false)
    {
        return $this->unprefixTube(parent::listTubeUsed($askServer));
    }

    /**
     * {@inheritDoc}
     */
    public function pauseTube($tube, $delay)
    {
        return parent::pauseTube($this->prefixTube($tube), $delay);
    }

    /**
     * {@inheritDoc}
     */
    public function peekReady($tube = null)
    {
        return parent::peekReady($this->prefixTube($tube));
    }

    /**
     * {@inheritDoc}
     */
    public function peekDelayed($tube = null)
    {
        return parent::peekDelayed($this->prefixTube($tube));
    }

    /**
     * {@inheritDoc}
     */
    public function peekBuried($tube = null)
    {
        return parent::peekBuried($this->prefixTube($tube));
    }

    /**
     * {@inheritDoc}
     */
    public function putInTube(
        $tube,
        $data,
        $priority = Pheanstalk_PheanstalkInterface::DEFAULT_PRIORITY,
        $delay = Pheanstalk_PheanstalkInterface::DEFAULT_DELAY,
        $ttr = Pheanstalk_PheanstalkInterface::DEFAULT_TTR
    )
    {
        return parent::putInTube($this->prefixTube($tube), $data, $priority, $delay, $ttr);
    }

    /**
     * {@inheritDoc}
     */
    public function reserveFromTube($tube, $timeout = null)
    {
        return parent::reserveFromTube($this->prefixTube($tube), $timeout);
    }

    /**
     * {@inheritDoc}
     */
    public function statsTube($tube)
    {
        return parent::statsTube($this->prefixTube($tube));
    }

    /**
     * {@inheritDoc}
     */
    public function useTube($tube)
    {
        return parent::useTube($this->prefixTube($tube));
    }

    /**
     * {@inheritDoc}
     */
    public function watch($tube)
    {
        return parent::watch($this->prefixTube($tube));
    }

    /**
     * {@inheritDoc}
     */
    public function watchOnly($tube)
    {
        return parent::watchOnly($this->prefixTube($tube));
    }

    //--- Helpers

    /**
     * Adding the prefix to the tube name.
     *
     * @param $tube
     * @return string
     */
    protected function prefixTube($tube)
    {
        return $this->tubePrefix . $tube;
    }

    /**
     * Removing the prefix from a tube name.
     * If prefix is not found, tube name will be returned unchanged.
     *
     * @param $tube
     * @return string
     */
    protected function unprefixTube($tube)
    {
        if ($this->tubePrefix && 0 === strpos($tube, $this->tubePrefix)) {
            return substr($tube, strlen($this->tubePrefix)) . '';
        }
        return $tube;
    }

    /**
     * Removing the prefix from a list of tube names.
     * Will only remove prefix, if parameter is a ARRAY.
     *
     * @param $tubes
     * @return array
     */
    protected function unprefixTubes($tubes)
    {
        if (!is_array($tubes)) {
            return $tubes;
        }
        return array_map(
            array($this, 'unprefixTube'),
            $tubes
        );
    }
}
 