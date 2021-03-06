<?php
namespace Ctimt\SqlControl\Listener\Logger;

use Ctimt\SqlControl\Enum\Messages;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
/* 
 * Copyright (C) 2016 David Schoenbauer <dschoenbauer@gmail.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

trait LoggerLevelValidateTrait{
    public function validateLogLevel($logLevel){
        if(!in_array($logLevel, [
            LogLevel::ALERT,  
            LogLevel::CRITICAL, 
            LogLevel::DEBUG, 
            LogLevel::EMERGENCY, 
            LogLevel::ERROR, 
            LogLevel::INFO, 
            LogLevel::NOTICE, 
            LogLevel::WARNING])){
        throw new InvalidArgumentException(printf(Messages::INVALID_LOG_LEVEL,$logLevel));
        }
    }
}