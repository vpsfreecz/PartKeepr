<?php
namespace PartKeepr\CoreBundle\Foobar;

/**
 * Special WebTestCase which forces to load the AppKernel. This is because the base getKernelClass() method
 * does wildcard matching on *Kernel.php within the app/ directory, and on some systems it might happen that
 * SetupAppKernel gets loaded first, causing unit tests to fail.
 */
class WebTestCase extends \Liip\FunctionalTestBundle\Test\WebTestCase
{
    protected static function getKernelClass()
    {
        $dir = isset($_SERVER['KERNEL_DIR']) ? $_SERVER['KERNEL_DIR'] : self::getPhpUnitXmlDir();

        $class = 'AppKernel';
        $file = $dir.'/'.$class.'.php';
        if (!file_exists($file)) {
            return parent::getKernelClass();
        }
        require_once $file;

        return $class;
    }

    protected function makeClient($authentication = false, array $params = array()) {
        $client = parent::makeClient($authentication, $params);
        $client->setServerParameter("CONTENT_TYPE", "application/json");
        $client->setServerParameter("HTTP_ACCEPT", "application/ld+json");

        return $client;

    }
}
