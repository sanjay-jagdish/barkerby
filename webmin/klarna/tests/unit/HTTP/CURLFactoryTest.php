<?php
/**
 * Copyright 2012 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing the PHPUnit Klarna_HTTP_CURLTest test case
 *
 * PHP version 5.2
 *
 * @category   Payment
 * @package    Payment_Klarna
 * @subpackage Unit_Tests
 * @author     Klarna <support@klarna.com>
 * @copyright  2012 Klarna AB
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link       http://integration.klarna.com/
 */

/**
 * PHPUnit test case for the HTTP CURL wrapper.
 *
 * @category   Payment
 * @package    Payment_Klarna
 * @subpackage Unit_Tests
 * @author     Klarna <support@klarna.com>
 * @copyright  2012 Klarna AB
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache license v2.0
 * @link       http://integration.klarna.com/
 */
class CURLFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that factory method returns a instance that implements
     * the wanted interface
     *
     * @return void
     */
    public function testFactoryResultImplements()
    {
        $factory = new Klarna_Checkout_HTTP_CURLFactory;
        $result = $factory->handle();
        $this->assertInstanceOf(
            'Klarna_Checkout_HTTP_CURLHandleInterface',
            $result
        );
    }
}
