<?php

/**
 * This file is part of the Wonolog package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Inpsyde\Wonolog;

use Inpsyde\Wonolog\Data\LogData;
use Monolog\Logger;

class LogActionUpdater
{
    public const ACTION_LOGGER_ERROR = 'wonolog.logger-error';

    /**
     * @var Channels
     */
    private $channels;

    /**
     * @param Channels $channels
     * @return LogActionUpdater
     */
    public static function new(Channels $channels): LogActionUpdater
    {
        return new static($channels);
    }

    /**
     * @param Channels $channels
     */
    private function __construct(Channels $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @param LogData $log
     * @return void
     */
    /**
     * @param LogData $log
     * @return void
     */
    public function update(LogData $log): void
    {
        if (
            !did_action(Configurator::ACTION_LOADED)
            || $log->level() < 1
            || $this->channels->isIgnored($log)
        ) {
            return;
        }

        try {
            $this->channels
                ->logger($log->channel())
                ->log($this->toPsrLevel($log->level()), $log->message(), $log->context());
        } catch (\Throwable $throwable) {
            /**
             * Fires when the logger encounters an error.
             *
             * @param LogData $log
             * @param \Exception|\Throwable $throwable
             */
            do_action(self::ACTION_LOGGER_ERROR, $log, $throwable);
        }
    }

    /**
     * @param int $monologLevel
     * @return string
     */
    private function toPsrLevel(int $monologLevel): string
    {
        switch ($monologLevel) {
            case Logger::EMERGENCY:
                return \Psr\Log\LogLevel::EMERGENCY;
            case Logger::ALERT:
                return \Psr\Log\LogLevel::ALERT;
            case Logger::CRITICAL:
                return \Psr\Log\LogLevel::CRITICAL;
            case Logger::ERROR:
                return \Psr\Log\LogLevel::ERROR;
            case Logger::WARNING:
                return \Psr\Log\LogLevel::WARNING;
            case Logger::NOTICE:
                return \Psr\Log\LogLevel::NOTICE;
            case Logger::INFO:
                return \Psr\Log\LogLevel::INFO;
        }

        return \Psr\Log\LogLevel::DEBUG;
    }
}