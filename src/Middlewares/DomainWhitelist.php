<?php

namespace Tkaratug\LaravelDomainWhitelist\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Tkaratug\LaravelDomainWhitelist\Traits\DomainParser;

class DomainWhitelist
{
    use DomainParser;

    public function handle(Request $request, Closure $next)
    {
        $whitelist = config('domain-whitelist');

        if (!empty($whitelist['domains']) && !in_array($request->getRequestUri(), $whitelist['excludes'])) {
            $domain = parse_url($request->headers->get('origin') ?? $request->headers->get('referer'), PHP_URL_HOST);

            if (!empty($domain) && !$this->isValidDomain($domain, $whitelist['domains'])) {
                $info = [
                    'host'  => $domain,
                    'ip'    => $request->getClientIp(),
                    'url'   => $request->getRequestUri(),
                    'agent' => $request->header('User-Agent'),
                ];

                Log::warning('Access from unauthorized domain - '.Carbon::now()->toDateTimeString(), $info);

                throw new SuspiciousOperationException('This host is not allowed!');
            }
        }

        return $next($request);
    }
}
