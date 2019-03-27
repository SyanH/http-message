<?php

namespace Mix\Http\Message\Base;

use Mix\Core\Component\AbstractComponent;

/**
 * Response组件基类
 * @author liu,jian <coder.keda@gmail.com>
 */
class Response extends AbstractComponent
{

    // 格式值
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_JSONP = 'jsonp';
    const FORMAT_XML = 'xml';
    const FORMAT_RAW = 'raw';

    // 默认输出格式
    public $defaultFormat;

    /**
     * @var \Mix\Http\Message\Json
     */
    public $json;

    /**
     * @var \Mix\Http\Message\Jsonp
     */
    public $jsonp;

    /**
     * @var \Mix\Http\Message\Xml
     */
    public $xml;

    // 当前输出格式
    public $format;

    // 状态码
    public $statusCode = 200;

    // 内容
    public $content = '';

    // HTTP 响应头
    public $headers = [];

    // 是否已经发送
    protected $_isSent = false;

    // 设置Header信息
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    // 预处理
    protected function prepare()
    {
        // 设置默认 Content-Type 信息
        $headers = array_change_key_case($this->headers, CASE_LOWER);
        if (!isset($headers['content-type'])) {
            switch ($this->format) {
                case self::FORMAT_HTML:
                    $this->setHeader('Content-Type', 'text/html; charset=utf-8');
                    break;
                case self::FORMAT_JSON:
                    $this->setHeader('Content-Type', 'application/json; charset=utf-8');
                    break;
                case self::FORMAT_JSONP:
                    $this->setHeader('Content-Type', 'application/json; charset=utf-8');
                    break;
                case self::FORMAT_XML:
                    $this->setHeader('Content-Type', 'text/xml; charset=utf-8');
                    break;
            }
        }
        // 转换内容为字符型
        $content = $this->content;
        is_null($content) and $content = '';
        if (is_array($content) || is_object($content)) {
            switch ($this->format) {
                case self::FORMAT_JSON:
                    $content = $this->json->encode($content);
                    break;
                case self::FORMAT_JSONP:
                    $content = $this->jsonp->encode($content);
                    break;
                case self::FORMAT_XML:
                    $content = $this->xml->encode($content);
                    break;
            }
        }
        $this->content = $content;
    }

}
