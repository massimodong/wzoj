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
�
wjudger.protoWJudger"]

SimpleArgs
token (	
code (	#
language (2.WJudger.Language
input (	"�
SimpleReply
compileError (
runtimeError (
compileErrorMessage (	
runtimeErrorMessage (	
timeused (

memoryused (
output (	"v
	JudgeArgs
code (	#
language (2.WJudger.Language
	timelimit (
memorylimit (
dataId ("�

JudgeReply2

resultType (2.WJudger.JudgeReply.ResultType-
compileResult (2.WJudger.CompileResult-
executeResult (2.WJudger.ExecuteResult"&

ResultType
COMPILE 
EXECUTE"7
CompileResult
compileEror (
	testcases (	"t
ExecuteResult
testcase (	
timeused (

memoryused (
score (
verdict (	
msg (	*<
Language
C 
CPP

PASCAL
JAVA

PYTHON2v
WJudger4
Judge.WJudger.JudgeArgs.WJudger.JudgeReply" 05
Simple.WJudger.SimpleArgs.WJudger.SimpleReply" bproto3'
        , true);

        static::$is_initialized = true;
    }
}

