<?php

namespace IMyxuan\Hymer\Tests;

use Illuminate\Support\Facades\Auth;

class LoginTest extends TestCase
{
    public function testSuccessfulLoginWithDefaultCredentials()
    {
        $this->visit(route('hymer.login'))
             ->type('admin@admin.com', 'email')
             ->type('password', 'password')
             ->press(__('hymer::generic.login'))
             ->seePageIs(route('hymer.dashboard'));
    }

    public function testShowAnErrorMessageWhenITryToLoginWithWrongCredentials()
    {
        session()->setPreviousUrl(route('hymer.login'));

        $this->visit(route('hymer.login'))
             ->type('john@Doe.com', 'email')
             ->type('pass', 'password')
             ->press(__('hymer::generic.login'))
             ->seePageIs(route('hymer.login'))
             ->see(__('auth.failed'))
             ->seeInField('email', 'john@Doe.com');
    }

    public function testRedirectIfLoggedIn()
    {
        Auth::loginUsingId(1);

        $this->visit(route('hymer.login'))
             ->seePageIs(route('hymer.dashboard'));
    }

    public function testRedirectIfNotLoggedIn()
    {
        $this->visit(route('hymer.profile'))
             ->seePageIs(route('hymer.login'));
    }

    public function testCanLogout()
    {
        Auth::loginUsingId(1);

        $this->visit(route('hymer.dashboard'))
             ->press(__('hymer::generic.logout'))
             ->seePageIs(route('hymer.login'));
    }

    public function testGetsLockedOutAfterFiveAttempts()
    {
        session()->setPreviousUrl(route('hymer.login'));

        for ($i = 0; $i <= 5; $i++) {
            $t = $this->visit(route('hymer.login'))
                 ->type('john@Doe.com', 'email')
                 ->type('pass', 'password')
                 ->press(__('hymer::generic.login'));
        }

        $t->see(__('auth.throttle', ['seconds' => 60]));
    }
}
