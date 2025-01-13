<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: wjudger.proto

namespace WJudger;

use UnexpectedValueException;

/**
 * Protobuf type <code>WJudger.Language</code>
 */
class Language
{
    /**
     * Generated from protobuf enum <code>C = 0;</code>
     */
    const C = 0;
    /**
     * Generated from protobuf enum <code>CPP = 1;</code>
     */
    const CPP = 1;
    /**
     * Generated from protobuf enum <code>PASCAL = 2;</code>
     */
    const PASCAL = 2;
    /**
     * Generated from protobuf enum <code>JAVA = 3;</code>
     */
    const JAVA = 3;
    /**
     * Generated from protobuf enum <code>PYTHON = 4;</code>
     */
    const PYTHON = 4;

    private static $valueToName = [
        self::C => 'C',
        self::CPP => 'CPP',
        self::PASCAL => 'PASCAL',
        self::JAVA => 'JAVA',
        self::PYTHON => 'PYTHON',
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

