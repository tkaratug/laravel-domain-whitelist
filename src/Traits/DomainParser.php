<?php

namespace Tkaratug\LaravelDomainWhitelist\Traits;

trait DomainParser
{
    /**
     * Check subdomains.
     *
     * @param string $first
     * @param string $last
     * @return bool
     */
    public function checkSubdomains(string $first, string $last): bool
    {
        return $first === '*' || empty($last) || $first === $last;
    }

    /**
     * Parse domain.
     *
     * @param string $domain
     * @return array
     */
    public function parseDomain(string $domain): array
    {
        $fullDomain = $domain;

        if (preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches)) {
            $domain = $matches['domain'];
        }

        return [
            'domain'    => $domain,
            'subdomain' => rtrim(strstr($fullDomain, $domain, true), '.'),
        ];
    }

    /**
     * Check whether the domain is in the whitelist.
     *
     * @param string $domain
     * @param array $whitelist
     * @return bool
     */
    public function isValidDomain(string $domain, array $whitelist): bool
    {
        $parsedDomain = $this->parseDomain($domain);

        foreach ($whitelist as $item) {
            $whitelistDomain = $this->parseDomain($item);

            if (
                $this->checkSubdomains($whitelistDomain['subdomain'], $parsedDomain['subdomain']) &&
                $whitelistDomain['domain'] === $parsedDomain['domain']
            ) {
                return true;
            }
        }

        return false;
    }

}
