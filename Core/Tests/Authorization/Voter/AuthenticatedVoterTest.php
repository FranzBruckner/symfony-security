<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Core\Tests\Authorization\Voter;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Tests\Fixtures\TokenInterface;

class AuthenticatedVoterTest extends TestCase
{
    /**
     * @dataProvider getVoteTests
     */
    public function testVote($authenticated, $attributes, $expected)
    {
        $voter = new AuthenticatedVoter(new AuthenticationTrustResolver());

        $this->assertSame($expected, $voter->vote($this->getToken($authenticated), null, $attributes));
    }

    public function getVoteTests()
    {
        return [
            ['fully', [], VoterInterface::ACCESS_ABSTAIN],
            ['fully', ['FOO'], VoterInterface::ACCESS_ABSTAIN],
            ['remembered', [], VoterInterface::ACCESS_ABSTAIN],
            ['remembered', ['FOO'], VoterInterface::ACCESS_ABSTAIN],
            ['anonymously', [], VoterInterface::ACCESS_ABSTAIN],
            ['anonymously', ['FOO'], VoterInterface::ACCESS_ABSTAIN],

            ['fully', ['IS_AUTHENTICATED_ANONYMOUSLY'], VoterInterface::ACCESS_GRANTED],
            ['remembered', ['IS_AUTHENTICATED_ANONYMOUSLY'], VoterInterface::ACCESS_GRANTED],
            ['anonymously', ['IS_AUTHENTICATED_ANONYMOUSLY'], VoterInterface::ACCESS_GRANTED],

            ['fully', ['IS_AUTHENTICATED_REMEMBERED'], VoterInterface::ACCESS_GRANTED],
            ['remembered', ['IS_AUTHENTICATED_REMEMBERED'], VoterInterface::ACCESS_GRANTED],
            ['anonymously', ['IS_AUTHENTICATED_REMEMBERED'], VoterInterface::ACCESS_DENIED],

            ['fully', ['IS_AUTHENTICATED_FULLY'], VoterInterface::ACCESS_GRANTED],
            ['remembered', ['IS_AUTHENTICATED_FULLY'], VoterInterface::ACCESS_DENIED],
            ['anonymously', ['IS_AUTHENTICATED_FULLY'], VoterInterface::ACCESS_DENIED],
        ];
    }

    protected function getToken($authenticated)
    {
        if ('fully' === $authenticated) {
            return $this->createMock(TokenInterface::class);
        } elseif ('remembered' === $authenticated) {
            return $this->getMockBuilder(RememberMeToken::class)->setMethods(['setPersistent'])->disableOriginalConstructor()->getMock();
        } else {
            return $this->getMockBuilder(AnonymousToken::class)->setConstructorArgs(['', ''])->getMock();
        }
    }
}
