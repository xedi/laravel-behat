<?php
namespace Xedi\Behat\Context\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

/**
 * Mailtrap Concern
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
trait MailTrap
{
    /**
     * The MailTrap configuration.
     *
     * @var integer
     */
    protected $mailtrap_inbox_id;

    /**
     * The MailTrap API Key.
     *
     * @var string
     */
    protected $mailtrap_api_key;

    /**
     * The Guzzle client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Get the configuration for MailTrap.
     *
     * @param integer|null $inbox_id Inbox ID
     *
     * @throws Exception
     *
     * @return void
     */
    protected function applyMailTrapConfiguration(int $inbox_id = null)
    {
        if (is_null($config = Config::get('services.mailtrap'))) {
            throw new Exception(
                'Set "secret" and "default_inbox" keys for "mailtrap" in "config/services.php."'
            );
        }

        $this->mailtrap_inbox_id = $inboxId ?: $config['default_inbox'];
        $this->mailtrap_api_key = $config['secret'];
    }

    /**
     * Fetch a MailTrap inbox.
     *
     * @param integer|null $inbox_id Inbox ID
     *
     * @throws RuntimeException
     *
     * @return mixed
     */
    protected function fetchInbox(int $inbox_id = null)
    {
        if (! $this->alreadyConfigured()) {
            $this->applyMailTrapConfiguration($inboxId);
        }

        $body = $this->requestClient()
            ->get($this->getMailTrapMessagesUrl())
            ->getBody();

        return $this->parseJson($body);
    }

    /**
     * Empty the MailTrap inbox.
     *
     * @AfterScenario @mail
     *
     * @return void
     */
    public function emptyInbox()
    {
        $this->requestClient()->patch($this->getMailTrapCleanUrl());
    }

    /**
     * Get the MailTrap messages endpoint.
     *
     * @return string
     */
    protected function getMailTrapMessagesUrl()
    {
        return "/api/v1/inboxes/{$this->mailtrap_inbox_id}/messages";
    }

    /**
     * Get the MailTrap "empty inbox" endpoint.
     *
     * @return string
     */
    protected function getMailTrapCleanUrl()
    {
        return "/api/v1/inboxes/{$this->mailtrap_inbox_id}/clean";
    }

    /**
     * Determine if MailTrap config has been retrieved yet.
     *
     * @return boolean
     */
    protected function alreadyConfigured()
    {
        return $this->mailtrap_api_key;
    }

    /**
     * Request a new Guzzle client.
     *
     * @return Client
     */
    protected function requestClient()
    {
        if (! $this->client) {
            $this->client = new Client([
                'base_uri' => 'https://mailtrap.io',
                'headers' => ['Api-Token' => $this->mailtrap_api_key]
            ]);
        }

        return $this->client;
    }

    /**
     * Parse JSON into array
     *
     * @param string $body JSON to be converted
     *
     * @throws RuntimeException
     *
     * @return array|mixed
     */
    protected function parseJson($body)
    {
        $data = json_decode((string) $body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(
                'Unable to parse response body into JSON: ' . json_last_error()
            );
        }

        return $data === null ? array() : $data;
    }
}
