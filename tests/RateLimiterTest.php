<?php

use PHPUnit\Framework\TestCase;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Laminas\Diactoros\ServerRequest as Request;
use Laminas\Diactoros\Response as Response;
use Laminas\Diactoros\Uri;

include_once('FakeStorage.php');
include_once('FakeHandler.php');

class RateLimiterTest extends TestCase {

    protected $storage = null;
    protected $handler = null;

    public function setUp() {
      $this->storage = new FakeStorage();
      $this->handler = new FakeHandler();
    }

    public function testIgnore() {

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnspecifiedCallback() {
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'throttle' => function() {}
        ]);
        $limiter();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnspecifiedThrottleCallback() {
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'callback' => function() {}
        ]);
        $limiter();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCallback() {
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'callback' => function($request) {

            },
            'throttle' => 'invalid_callback'
        ], $this->storage);
        $request = (new Request)
            ->withUri(new Uri("https://example.com/api"))
            ->withMethod("GET");
        $limiter($request, $this->handler);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidThrottle() {
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'callback' => function($request) {
                return [
                    'key' => uniqid()
                ];
            },
            'throttle' => 'invalid_callback'
        ], $this->storage);
        $request = (new Request)
            ->withUri(new Uri("https://example.com/api"))
            ->withMethod("GET");
        $limiter($request, $this->handler);
    }

    public function testMetaAsTrue() {

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMetaNotArray() {
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'callback' => function($request) {
                return 'testing';
            },
            'throttle' => '',
        ], $this->storage);
        $request = (new Request)
            ->withUri(new Uri("https://example.com/api"))
            ->withMethod("GET");
        $limiter($request, $this->handler);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMetaDoesNotContainTokenKey() {
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'callback' => function($request) {
                return [
                    'testing' => true
                ];
            },
            'throttle' => '',
        ], $this->storage);
        $request = (new Request)
            ->withUri(new Uri("https://example.com/api"))
            ->withMethod("GET");
        $limiter($request, $this->handler);
    }

    public function testBucketIsFull() {

    }


    public function testDefaultHeader() {
        $result = null;
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'callback' => function($request) {
                return [
                    'token' => uniqid()
                ];
            },
            'throttle' => ''
        ], $this->storage);
        $request = (new Request)
            ->withUri(new Uri("https://example.com/api"))
            ->withMethod("GET");
        $response = $limiter($request, $this->handler);
        $this->assertContains("X-Rate-Limit", array_keys($response->getHeaders()));
    }

    public function testCustomHeader() {
        $result = null;
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'callback' => function($request) {
                return [
                    'token' => uniqid()
                ];
            },
            'throttle' => '',
            'header' => 'X-Api-Rate-Limit'
        ], $this->storage);
        $request = (new Request)
            ->withUri(new Uri("https://example.com/api"))
            ->withMethod("GET");

        $response = $limiter($request, $this->handler);

        $this->assertContains(
          "X-Api-Rate-Limit",
          array_keys($response->getHeaders())
        );
    }

    public function testDisabledHeader() {
        $result = null;
        $limiter = new LeakyBucketRateLimiter\RateLimiter([
            'callback' => function($request) {
                return [
                    'token' => uniqid()
                ];
            },
            'throttle' => '',
            'header' => false
        ], $this->storage);
        $request = (new Request)
            ->withUri(new Uri("https://example.com/api"))
            ->withMethod("GET");
        $response = $limiter($request, $this->handler);
        $this->assertEmpty(array_keys($response->getHeaders()));
    }

    public function testPrefix() {

    }

    public function testSuffix() {

    }
}
