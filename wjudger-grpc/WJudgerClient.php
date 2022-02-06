<?php
// GENERATED CODE -- DO NOT EDIT!

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
     * @param \JudgeArgs $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\ServerStreamingCall
     */
    public function Judge(\JudgeArgs $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/WJudger/Judge',
        $argument,
        ['\JudgeReply', 'decode'],
        $metadata, $options);
    }

}
