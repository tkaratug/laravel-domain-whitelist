<?php

namespace Tkaratug\LaravelDomainWhitelist\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Tkaratug\LaravelDomainWhitelist\Middlewares\DomainWhitelist;

class DomainWhitelistTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_allows_requests_from_domains_that_in_the_whitelist()
    {
        $request = new Request();

        $domain = $this->faker->domainName;

        config()->set('domain-whitelist.domains', [$domain]);

        $request->headers->set('origin', 'https://'.$domain);

        $middleware = new DomainWhitelist();

        $response = $middleware->handle($request, function() {});

        $this->assertNull($response);
    }

    /** @test */
    public function it_allows_requests_if_domain_whitelist_is_empty()
    {
        $request = new Request();

        $domain = $this->faker->domainName;

        config()->set('domain-whitelist.domains', []);

        $request->headers->set('origin', 'https://'.$domain);

        $middleware = new DomainWhitelist();

        $response = $middleware->handle($request, function() {});

        $this->assertNull($response);
    }

    /** @test */
    public function it_allows_requests_if_domain_is_null()
    {
        $request = new Request();

        $domain = $this->faker->domainName;

        config()->set('domain-whitelist.domains', [$domain]);

        $middleware = new DomainWhitelist();

        $response = $middleware->handle($request, function() {});

        $this->assertNull($response);
    }

    /** @test */
    public function it_allows_requests_from_subdomains_that_in_the_whitelist()
    {
        $request = new Request();

        $domain = $this->faker->domainName;

        config()->set('domain-whitelist.domains', ['*.'.$domain]);

        $request->headers->set('origin', 'https://'.$this->faker->word.'.'.$domain);

        $middleware = new DomainWhitelist();

        $response = $middleware->handle($request, function () {
        });

        $this->assertNull($response);
    }

    /** @test */
    public function it_allows_requests_from_domains_that_not_in_the_whitelist_if_request_uri_is_allowed()
    {
        $request = new Request();
        $request->server->set('REQUEST_URI', '/api/posts');

        $domain = $this->faker->domainName;

        config()->set('domain-whitelist.domains', [$domain]);
        config()->set('domain-whitelist.excludes', ['/api/posts']);

        $request->headers->set('origin', 'https://'.$this->faker->domainName);

        $middleware = new DomainWhitelist();

        $response = $middleware->handle($request, function () {
        });

        $this->assertNull($response);
    }

    /** @test */
    public function it_blocks_requests_from_subdomains_that_not_in_the_whitelist()
    {
        $request = new Request();

        $domain = $this->faker->domainName;

        config()->set('domain-whitelist.domains', ['api.'.$domain]);

        $request->headers->set('origin', 'https://'.$this->faker->word.'.'.$domain);

        $middleware = new DomainWhitelist();

        $this->expectException(SuspiciousOperationException::class);
        Log::shouldReceive('warning');

        $middleware->handle($request, function () {
        });
    }

    /** @test */
    public function it_blocks_requests_from_domains_that_not_in_the_whitelist()
    {
        $request = new Request();

        $domain = $this->faker->domainName;

        config()->set('domain-whitelist.domains', [$domain]);

        $request->headers->set('origin', 'https://'.$this->faker->domainName);

        $middleware = new DomainWhitelist();

        $this->expectException(SuspiciousOperationException::class);
        Log::shouldReceive('warning');

        $middleware->handle($request, function () {
        });
    }
}
