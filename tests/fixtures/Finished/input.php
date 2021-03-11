<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Register')
                    ->pause(500)
                    ->type('name', 'Mateus Guimaraes')
                    ->type('email', 'mateus@mateusguimaraes.com')
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password')
                    ->radio('input[name=some_radio]', 'Option 1')
                    ->press('REGISTER')
                    ->magic();
        });
    }
}
