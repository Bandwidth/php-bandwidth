<?php
/**
 * REST Client
 *
 */

namespace Bandwidth;

use GuzzleHttp\Exception\ClientException;
use SimpleXMLElement;

class ResponseException extends \Exception
{

}

abstract class iClient
{
    abstract function get($url, $options);

    abstract function post($url, $base_node, $data);

    abstract function put($url, $base_node, $data);

    abstract function delete($url);
}

final class CatapultClient extends iClient
{
    public function __construct($user_id, $token, $secret, $options = [])
    {
        if (empty($user_id) || empty($token) || empty($secret))
        {
            throw new \Exception("Provide user_id, token, secret");
        }

        $options['auth'] = [$token, $secret];
        $options['base_uri'] = $options['url'] ?: 'https://api.catapult.inetwork.com/v1';
        unset($options['url']);
        $options['base_uri'] = rtrim($options['base_uri'], '/') . '/';

        $client_options = array();
        if(isset($options['handler'])) {
            $client_options['handler'] = $options['handler'];
        }

        $this->client = new \GuzzleHttp\Client($options);

        $options['base_uri'] = $options['v2_url'] ?: 'https://api.catapult.inetwork.com/v2';
        unset($options['v2_url']);
        $options['base_uri'] = rtrim($options['base_uri'], '/') . '/';

        $this->v2_client = new \GuzzleHttp\Client($options);
    }
}

final class IrisClient extends iClient
{
    public function __construct($login, $password, $options = [])
    {
        if (empty($login) || empty($password))
        {
            throw new \Exception("Provide login, password");
        }

        $options['auth'] = [$login, $password];
        $options['base_uri'] = $options['url'] ?: 'https://api.inetwork.com/v1.0';
        unset($options['url']);
        $options['base_uri'] = rtrim($options['base_uri'], '/') . '/';

        $client_options = array();
        if(isset($options['handler'])) {
            $client_options['handler'] = $options['handler'];
        }

        $this->client = new \GuzzleHttp\Client($options);
    }

    /**
     * Wrapper method for GET request
     *
     * @param string $url
     * @param array  $options
     *
     * @return array
     * @throws ResponseException
     */
    public function get($url, $options = [])
    {
        return $this->request('get', $url, $options);
    }

    /**
     * Wrapper method for POST request
     *
     * @param string $url
     * @param string $baseNode
     * @param array  $data
     *
     * @return array
     * @throws ResponseException
     */
    public function post($url, $baseNode, $data)
    {
        $options = [
            'body'    => $this->prepareXmlBody($data, $baseNode),
            'headers' => ['Content-Type' => 'application/xml']
        ];
        return $this->request('post', $url, $options);
    }

    /**
     * Wrapper method for PUT request
     *
     * @param string $url
     * @param string $baseNode
     * @param array  $data
     *
     * @return array
     * @throws ResponseException
     */
    public function put($url, $baseNode, $data)
    {
        $options = [
            'body'    => $this->prepareXmlBody($data, $baseNode),
            'headers' => ['Content-Type' => 'application/xml']

        ];
        return $this->request('put', $url, $options);
    }

    /**
     * Wrapper method for DELETE request
     *
     * @param string $url
     * @throws ResponseException
     */
    public function delete($url)
    {
        $this->request('delete', $url);
    }

    /**
     * Wrapper method for POST file send
     *
     * @param string $url
     * @param string $content
     * @param array  $headers
     *
     * @return mixed|string
     * @throws ResponseException
     */
    public function raw_file_post($url, $content, $headers = [])
    {
        $options = [
            'body'    => $content,
            'headers' => $headers
        ];
        $response = $this->request('post', $url, $options, false);

        if (!isset($response['Location']))
        {
            return '';
        }
        return $response['Location'];
    }

    /**
     * Wrapper method for PUT file send
     *
     * @param string $url
     * @param string $content
     * @param array  $headers
     *
     * @return mixed|string
     * @throws ResponseException
     */
    public function raw_file_put($url, $content, $headers = [])
    {
        $options = [
            'body'    => $content,
            'headers' => $headers
        ];
        $response = $this->request('put', $url, $options, false);

        if ($response->hasHeader('Location'))
        {
            return reset($response->getHeader('Location'));
        }
        return '';
    }

    /**
     * Do request and parse xml response or error
     *
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @return \GuzzleHttp\Psr7\Stream|array
     * @throws ResponseException
     */
    public function request($method, $url, $options = [], $parse = true)
    {
        try
        {
            $response = $this->client->request($method, ltrim($url, '/'), $options);
            if (!$parse)
            {
                return $response;
            }
            return $this->parseResponse($response);
        }
        catch (ClientException $e)
        {
            $this->parseExceptionResponse($e);
        }
    }

    /**
     * Prepare XML string body
     *
     * @param array $data
     * @param string $baseNode
     *
     * @return string
     */
    private function prepareXmlBody($data, $baseNode)
    {
        $xml = new SimpleXMLElement(sprintf('<%s/>', $baseNode));
        if (is_string($data))
        {
            $xml[0] = $data;
        }
        else
        {
            $this->array2xml($data, $xml);
        }

        return $xml->asXML();
    }

    /**
     * Convert response body to array
     *
     * @param \GuzzleHttp\Psr7\Stream $response
     *
     * @return array
     */
    private function parseResponse($response)
    {
        $responseBody = (string) $response->getBody();

        if (!$responseBody && $response->hasHeader('Location'))
        {
            $location = $response->getHeader('Location');
            return ['Location' => reset($location)];
        }

        if (!$responseBody)
        {
            return [];
        }

        $contentType = $response->getHeader('Content-Type');
        $contentType = reset($contentType);

        if ($contentType && strpos($contentType, 'json') !== false)
        {
            return json_decode($responseBody, true);
        }

        try
        {
            $xml = new SimpleXMLElement($responseBody);
            return $this->xml2array($xml);
        }
        catch (Exception $e)
        {
            return [];
        }
    }

    /**
     * @param ClientException $e
     *
     * @throws ResponseException
     */
    private function parseExceptionResponse($e)
    {
        $body = $e->getResponse()->getBody(true);
        $doc = @simplexml_load_string($body);

        if (isset($doc) &&
            isset($doc->ResponseStatus) &&
            isset($doc->ResponseStatus->Description))
        {
            throw new ResponseException(
                (string) $doc->ResponseStatus->Description,
                (int) $doc->ResponseStatus->ErrorCode
            );
        }

        if (isset($doc) &&
            isset($doc->Error) &&
            isset($doc->Error->Description) &&
            isset($doc->Error->Code))
        {
            throw new ResponseException(
                (string) $doc->Error->Description,
                (int) $doc->Error->Code
            );
        }

        throw new ResponseException($body, $e->getResponse()->getStatusCode());
    }

    protected function xml2object($xmlObject)
    {
        /* snippet: http://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php */

        $arr = $this->xml2array($xmlObject);
        $object = json_decode(json_encode($arr), false);

        return $object;
    }

    protected function xml2array($xml)
    {
        $arr = [];
        foreach ($xml as $element)
        {
            $tag = $element->getName();
            $e = get_object_vars($element);
            if (!empty($e))
            {
                $res = $element instanceof \SimpleXMLElement ? $this->xml2array($element) : $e;
            }
            else
            {
                $res = trim($element);
            }

            if (isset($arr[$tag]))
            {
                if (!is_array($arr[$tag]) || $this->isAssoc($arr[$tag]))
                {
                    $tmp = $arr[$tag];
                    $arr[$tag] = [];
                    $arr[$tag][] = $tmp;
                }
                $arr[$tag][] = $res;
            }
            else
            {
                $arr[$tag] = $res;
            }

        }

        return $arr;
    }

    protected function isAssoc($array)
    {
        $array = array_keys($array);

        return ($array !== array_keys($array));
    }

    protected function array2xml($arr, &$xml)
    {
        /* snippet:  http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml */
        foreach ($arr as $key => $value)
        {
            if (is_array($value) && $this->isAssoc($value))
            {
                if (!is_numeric($key))
                {
                    $subnode = $xml->addChild("$key");
                    $this->array2xml($value, $subnode);
                }
                else
                {
                    $subnode = $xml->addChild("item$key");
                    $this->array2xml($value, $subnode);
                }
            }
            else
            {
                if (is_array($value) && !$this->isAssoc($value))
                {
                    foreach ($value as $item)
                    {
                        if (is_array($item))
                        {
                            $subnode = $xml->addChild("$key");
                            $this->array2xml($item, $subnode);
                        }
                        else
                        {
                            $xml->addChild("$key", htmlspecialchars("$item"));
                        }
                    }
                }
                else
                {
                    $xml->addChild("$key", htmlspecialchars("$value"));
                }
            }
        }
    }
}
