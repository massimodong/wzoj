<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: wjudger.proto

namespace WJudger\JudgeReply;

use UnexpectedValueException;

/**
 * Protobuf type <code>WJudger.JudgeReply.ResultType</code>
 */
class ResultType
{
    /**
     * Generated from protobuf enum <code>COMPILE = 0;</code>
     */
    const COMPILE = 0;
    /**
     * Generated from protobuf enum <code>EXECUTE = 1;</code>
     */
    const EXECUTE = 1;

    private static $valueToName = [
        self::COMPILE => 'COMPILE',
        self::EXECUTE => 'EXECUTE',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

