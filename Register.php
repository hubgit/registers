<?php

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;

/**
 *
 */
class Register
{
    /** @var string */
    private $url;

    /** @var Client */
    private $client;

    /**
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;

        $this->client = new Client([
            //'debug' => true
        ]);
    }

    /**
     * @param array  $query
     * @param string $path
     *
     * @throws Exception
     */
    public function get($query = [], $path = 'php://output')
    {
        $output = fopen($path, 'w');

        do {
            $response = $this->client->get($this->url, [
                'query' => $query
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new Exception();
            }

            $body = $response->getBody();

            // header row
            $line = Psr7\readline($body);
            $keys = str_getcsv($line);

            while ($line = Psr7\readline($body)) {
                $row = str_getcsv($line);
                $data = array_combine($keys, $row);
                fwrite($output, json_encode($data) . "\n");
            }

            $query = $this->next($response);
        } while ($query);

        fclose($output);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return string|null
     */
    private function next(ResponseInterface $response)
    {
        $links = Psr7\parse_header($response->getHeader('Link'));

        foreach ($links as $link) {
            if ($link['rel'] == 'next') {
                return trim($link[0], '<>');
            }
        }

        return null;
    }
}
