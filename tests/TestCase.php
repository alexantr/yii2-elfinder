<?php

namespace tests;

use Yii;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\AssetManager;
use yii\web\View;

/**
 * This is the base class for all yii framework unit tests.
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    public static $params;

    /**
     * Mock application prior running tests.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
    }

    /**
     * Clean up after test.
     * By default the application created with [[mockApplication]] will be destroyed.
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::removeAssets();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
        ], $config));
    }

    protected function mockWebApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'components' => [
                'request' => [
                    'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php',
                ],
            ],
        ], $config));
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
        Yii::$container = new Container();
    }

    /**
     * Remove assets
     */
    protected static function removeAssets()
    {
        $assets_path = Yii::getAlias('@tests/data/assets');
        if ($assets_path && $handle = opendir($assets_path)) {
            while (($file = readdir($handle)) !== false) {
                if (strpos($file, '.') === 0) continue;
                $path = $assets_path . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    FileHelper::removeDirectory($path);
                }
            }
            closedir($handle);
        }
    }

    /**
     * Mocks view instance
     * @return View
     */
    protected function mockView()
    {
        return new View([
            'assetManager' => $this->mockAssetManager(),
        ]);
    }

    /**
     * Mocks asset manager
     * @return AssetManager
     */
    protected function mockAssetManager()
    {
        return new AssetManager([
            'basePath' => '@tests/data/assets',
            'baseUrl' => '/assets',
        ]);
    }

    /**
     * Asserting two strings equality ignoring line endings
     * @param string $expected
     * @param string $actual
     */
    public function assertEqualsWithoutLE($expected, $actual)
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Asserts that a haystack contains a needle ignoring line endings
     * @param string $expected
     * @param string $actual
     */
    public function assertContainsWithoutLE($expected, $actual)
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);
        $this->assertContains($expected, $actual);
    }
}
