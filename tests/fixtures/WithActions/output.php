<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel')
                    ->clickLink('Log in')
                    ->pause(500)
                    ->clickLink('Forgot your password?')
                    ->pause(500)
                    ->assertSee('Mateus')
                    ->magic();
        });
    }
}
