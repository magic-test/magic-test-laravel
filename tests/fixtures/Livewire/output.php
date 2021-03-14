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
                    ->type('name', 'Mateus')
                    ->pause(200)
                    ->type('email', 'mateus@mateusguimaraes.com')
                    ->pause(200)
                    ->magic();
        });
    }
}
