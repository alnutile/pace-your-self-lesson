<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AgreeToTermsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testTerms()
    {
        Artisan::call('feature:on database agrees_to_terms_ui');

        $this->browse(function (Browser $browser) {
            $browser->visit(route('register'))
                    ->waitForText('Agree to Terms')
                    ->assertSee('Agree to Terms')
                    ->type('@name', 'Foo Bar')
                    ->type('@email', 'foo@bar.com')
                    ->type('@password', 'password')
                    ->type('@password_confirmation', 'password')
                    ->check('@agrees_to_terms')
                    ->press('@register')
                    ->pause(3000)
                    ->assertAuthenticated();
        });
    }
}
