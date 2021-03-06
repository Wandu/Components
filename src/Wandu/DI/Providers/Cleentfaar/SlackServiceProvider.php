<?php
namespace Wandu\DI\Providers\Cleentfaar;

use CL\Slack\Transport\ApiClient;
use Wandu\Config\Contracts\Config;
use Wandu\DI\ContainerInterface;
use Wandu\DI\ServiceProviderInterface;

class SlackServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(ContainerInterface $app)
    {
        $app->closure(ApiClient::class, function (Config $config) {
            return new ApiClient($config->get('cleentfaar.slack.token'));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(ContainerInterface $app)
    {
    }
}
