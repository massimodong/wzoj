<?php
// GENERATED CODE -- DO NOT EDIT!

namespace WJudger;

/**
 */
class WJudgerClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \WJudger\JudgeArgs $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\ServerStreamingCall
     */
    public function Judge(\WJudger\JudgeArgs $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/WJudger.WJudger/Judge',
        $argument,
        ['\WJudger\JudgeReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \WJudger\SimpleArgs $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Simple(\WJudger\SimpleArgs $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/WJudger.WJudger/Simple',
        $argument,
        ['\WJudger\SimpleReply', 'decode'],
        $metadata, $options);
    }

}
