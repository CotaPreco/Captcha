<?php

/*
 * Copyright (c) 2015 Cota Preço
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CotaPreco\Captcha\Solver\DeathByCaptcha;

use CotaPreco\Captcha\CaptchaInterface;
use CotaPreco\Captcha\Solver\DeathByCaptcha\Exception\ServiceTemporarilyOverloadedException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Message\RequestInterface;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class OnServiceUnavailableTest extends TestCase
{
    /**
     * @test
     */
    public function requestSolutionForCaptcha()
    {
        $this->setExpectedException(ServiceTemporarilyOverloadedException::class);

        /* @var RequestInterface $request */
        $request = $this->getMock(RequestInterface::class);

        /* @var \PHPUnit_Framework_MockObject_MockObject|DeathByCaptchaInterface $deathByCaptcha */
        $deathByCaptcha = $this->getMock(DeathByCaptchaInterface::class);

        $deathByCaptcha->expects($this->once())
            ->method('requestSolutionForCaptcha')
            ->with($this->isInstanceOf(CaptchaInterface::class))
            ->willThrowException(new ServerException('', $request));

        /* @var CaptchaInterface $captcha */
        $captcha = $this->getMock(CaptchaInterface::class);

        $onServiceUnavailable = new OnServiceUnavailable($deathByCaptcha);

        $onServiceUnavailable->requestSolutionForCaptcha($captcha);
    }
}
