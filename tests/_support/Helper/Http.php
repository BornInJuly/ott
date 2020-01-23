<?php
namespace Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module\PhpBrowser;
use GuzzleHttp\Psr7\Uri as Psr7Uri;

class Http extends \Codeception\Module
{
    /** @var PhpBrowser */
    private $phpBrowserModule;

    /**
     * @throws ModuleException
     */
    public function _initialize()
    {
        $this->phpBrowserModule = $this->getModule('PhpBrowser');
    }

    /**
     * Return full URL (included protocol, host etc)
     *
     * @return string
     * @throws \Codeception\Exception\ModuleException
     */
    public function grabFullCurrentUrl()
    {
        return $this->retrieveFullUri();
    }

    /**
     * @return string
     * @throws \Codeception\Exception\ModuleException
     */
    public function retrieveFullUri()
    {
        $url = $this->phpBrowserModule->_getURL();
        if ($url == 'about:blank' || strpos($url, 'data:') === 0) {
            throw new ModuleException($this, 'Current url is blank, no page was opened');
        }

        $uri = new Psr7Uri($url);
        return (string)(new Psr7Uri())
            ->withHost($uri->getHost())
            ->withScheme($uri->getScheme())
            ->withPath($uri->getPath())
            ->withQuery($uri->getQuery())
            ->withFragment($uri->getFragment());
    }

    /**
     * Checks if the current full url matches the given pattern
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function seeCurrentFullUrlMatches(string $fullUrl)
    {
        $this->assertContains($fullUrl, $this->retrieveFullUri());
    }
}
