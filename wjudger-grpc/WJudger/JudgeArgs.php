<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: wjudger.proto

namespace WJudger;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>WJudger.JudgeArgs</code>
 */
class JudgeArgs extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string code = 1;</code>
     */
    protected $code = '';
    /**
     * Generated from protobuf field <code>.WJudger.JudgeArgs.Language language = 2;</code>
     */
    protected $language = 0;
    /**
     * Generated from protobuf field <code>uint64 timelimit = 3;</code>
     */
    protected $timelimit = 0;
    /**
     * Generated from protobuf field <code>double memorylimit = 4;</code>
     */
    protected $memorylimit = 0.0;
    /**
     * Generated from protobuf field <code>uint32 dataId = 5;</code>
     */
    protected $dataId = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $code
     *     @type int $language
     *     @type int|string $timelimit
     *     @type float $memorylimit
     *     @type int $dataId
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Wjudger::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string code = 1;</code>
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Generated from protobuf field <code>string code = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->code = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.WJudger.JudgeArgs.Language language = 2;</code>
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Generated from protobuf field <code>.WJudger.JudgeArgs.Language language = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setLanguage($var)
    {
        GPBUtil::checkEnum($var, \WJudger\JudgeArgs\Language::class);
        $this->language = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 timelimit = 3;</code>
     * @return int|string
     */
    public function getTimelimit()
    {
        return $this->timelimit;
    }

    /**
     * Generated from protobuf field <code>uint64 timelimit = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTimelimit($var)
    {
        GPBUtil::checkUint64($var);
        $this->timelimit = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>double memorylimit = 4;</code>
     * @return float
     */
    public function getMemorylimit()
    {
        return $this->memorylimit;
    }

    /**
     * Generated from protobuf field <code>double memorylimit = 4;</code>
     * @param float $var
     * @return $this
     */
    public function setMemorylimit($var)
    {
        GPBUtil::checkDouble($var);
        $this->memorylimit = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 dataId = 5;</code>
     * @return int
     */
    public function getDataId()
    {
        return $this->dataId;
    }

    /**
     * Generated from protobuf field <code>uint32 dataId = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setDataId($var)
    {
        GPBUtil::checkUint32($var);
        $this->dataId = $var;

        return $this;
    }

}
