<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: wjudger.proto

namespace GPBMetadata;

class Wjudger
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
wjudger.protoWJudger"�
	JudgeArgs
code (	-
language (2.WJudger.JudgeArgs.Language
	timelimit (
memorylimit (
dataId ("<
Language
C 
CPP

PASCAL
JAVA

PYTHON"�

JudgeReply2

resultType (2.WJudger.JudgeReply.ResultType-
compileResult (2.WJudger.CompileResult-
executeResult (2.WJudger.ExecuteResult"&

ResultType
COMPILE 
EXECUTE"9
CompileResult
compile_error (
	testcases (	"t
ExecuteResult
testcase (	
timeused (

memoryused (
score (
verdict (	
msg (	2?
WJudger4
Judge.WJudger.JudgeArgs.WJudger.JudgeReply" 0bproto3'
        , true);

        static::$is_initialized = true;
    }
}

