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

namespace Inpsyde\Wonolog\Data;

use Monolog\Logger;

/**
 * A log event with predefined level set to NOTICE.
 *
 * @package wonolog
 * @license http://opensource.org/licenses/MIT MIT
 */
final class Notice implements LogDataInterface
{
    use LogDataTrait;

    public function level(): int
    {
        return Logger::NOTICE;
    }
}
